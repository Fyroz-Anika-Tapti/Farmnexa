<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalFeedRecord;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnimalFeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function bulkCreate()
{
    $items = InventoryItem::where('status', 'active')
        ->where('category', 'Feed')
        ->where('current_stock', '>', 0)
        ->get();

    $animals = Animal::where('status', 'active')
        ->with('farm')
        ->orderBy('animal_code')
        ->get();

    return view('animal_feeds.bulk_create', compact('items', 'animals'));
}

public function bulkStore(Request $request)
{
    $data = $request->validate([
        'inventory_item_id' => 'required|exists:inventory_items,id',
        'feed_date'         => 'required|date',
        'quantity'          => 'required|numeric|min:0.01',
        'animal_ids'        => 'required|array|min:1',
        'animal_ids.*'      => 'exists:animals,id',
        'notes'             => 'nullable|string',
    ]);

    $invItem = InventoryItem::findOrFail($data['inventory_item_id']);
    $animalsCount = count($data['animal_ids']);
    $totalQty    = $data['quantity'] * $animalsCount;

    if ($invItem->current_stock < $totalQty) {
        return back()->withErrors([
            'quantity' => "Not enough stock! Required: {$totalQty} {$invItem->unit}, Available: {$invItem->current_stock} {$invItem->unit}"
        ])->withInput();
    }

    try {
        DB::transaction(function () use ($data, $invItem, $totalQty) {
            $stockBefore = $invItem->current_stock;
            $stockAfter  = $stockBefore - $totalQty;
            $totalCost   = $totalQty * $invItem->purchase_price;

            // ১. Stock Movement (Bulk)
            $movement = StockMovement::create([
                'inventory_item_id' => $invItem->id,
                'farm_id'           => $data['farm_id'] ?? 1,
                'movement_date'     => $data['feed_date'],
                'movement_type'     => 'out',
                'quantity'          => $totalQty,
                'unit_price'        => $invItem->purchase_price,
                'total_value'       => $totalCost,
                'stock_before'      => $stockBefore,
                'stock_after'       => $stockAfter,
                'reference_type'    => 'bulk_feed',
                'reference_no'      => 'BULK-' . date('YmdHis'),
                'notes'             => 'Bulk feed for ' . count($data['animal_ids']) . ' animals',
                'recorded_by'       => Auth::id(),
            ]);

            // ২. Stock কমাও
            $invItem->update(['current_stock' => $stockAfter]);

            // ৩. প্রতিটা animal-এর জন্য feed record
            foreach ($data['animal_ids'] as $animalId) {
                $animal = Animal::find($animalId);
                AnimalFeedRecord::create([
                    'animal_id'         => $animal->id,
                    'inventory_item_id' => $invItem->id,
                    'stock_movement_id' => $movement->id,
                    'feed_date'         => $data['feed_date'],
                    'quantity'          => $data['quantity'],
                    'unit'              => $invItem->unit,
                    'unit_price'        => $invItem->purchase_price,
                    'total_cost'        => $data['quantity'] * $invItem->purchase_price,
                    'notes'             => $data['notes'] ?? null,
                    'recorded_by'       => Auth::id(),
                ]);
            }
        });
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }

    return redirect()->route('animals.index')
        ->with('success', "Bulk feed saved for {$animalsCount} animals. Total: {$totalQty} {$invItem->unit} = " . number_format($totalCost ?? 0, 2) . " BDT");
}

    public function index(Animal $animal)
    {
        $records = $animal->feedRecords()
            ->with('inventoryItem')
            ->latest('feed_date')
            ->paginate(15);
        return view('animal_feeds.index', compact('animal', 'records'));
    }

    public function create(Animal $animal)
    {
        $items = InventoryItem::where('status', 'active')
            ->where('category', 'Feed')
            ->where('current_stock', '>', 0)
            ->get();
        return view('animal_feeds.create', compact('animal', 'items'));
    }

    public function store(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'feed_date'         => 'required|date',
            'quantity'          => 'required|numeric|min:0.01',
            'notes'             => 'nullable|string',
        ]);

        $invItem = InventoryItem::findOrFail($data['inventory_item_id']);

        if ($invItem->current_stock < $data['quantity']) {
            return back()->withErrors([
                'quantity' => "Not enough stock! Available: {$invItem->current_stock} {$invItem->unit}"
            ])->withInput();
        }

        try {
            DB::transaction(function () use ($data, $animal, $invItem) {
                $stockBefore = $invItem->current_stock;
                $stockAfter  = $stockBefore - $data['quantity'];
                $totalCost   = $data['quantity'] * $invItem->purchase_price;

                $movement = StockMovement::create([
                    'inventory_item_id' => $invItem->id,
                    'farm_id'           => $animal->farm_id,
                    'movement_date'     => $data['feed_date'],
                    'movement_type'     => 'out',
                    'quantity'          => $data['quantity'],
                    'unit_price'        => $invItem->purchase_price,
                    'total_value'       => $totalCost,
                    'stock_before'      => $stockBefore,
                    'stock_after'       => $stockAfter,
                    'reference_type'    => 'animal_feed',
                    'reference_no'      => 'FEED-' . $animal->animal_code,
                    'notes'             => 'Feed for animal: ' . $animal->animal_code,
                    'recorded_by'       => Auth::id(),
                ]);

                $invItem->update(['current_stock' => $stockAfter]);

                AnimalFeedRecord::create([
                    'animal_id'         => $animal->id,
                    'inventory_item_id' => $invItem->id,
                    'stock_movement_id' => $movement->id,
                    'feed_date'         => $data['feed_date'],
                    'quantity'          => $data['quantity'],
                    'unit'              => $invItem->unit,
                    'unit_price'        => $invItem->purchase_price,
                    'total_cost'        => $totalCost,
                    'notes'             => $data['notes'] ?? null,
                    'recorded_by'       => Auth::id(),
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('animals.feeds.index', $animal)
            ->with('success', 'Feed recorded successfully!');
    }

    public function destroy(AnimalFeedRecord $record)
    {
        $animalId = $record->animal_id;

        try {
            DB::transaction(function () use ($record) {
                if ($record->stockMovement) {
                    $movement = $record->stockMovement;
                    $invItem  = $movement->item;
                    if ($invItem) {
                        $invItem->current_stock += $movement->quantity;
                        $invItem->save();
                    }
                    $movement->delete();
                }
                $record->delete();
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('animals.feeds.index', $animalId)
            ->with('success', 'Feed record deleted & stock restored!');
    }
}