<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\InventoryItem;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $movements = StockMovement::with(['item', 'farm'])
            ->latest('movement_date')->latest('id')->paginate(20);
        return view('stock_movements.index', compact('movements'));
    }

    public function create()
    {
        $items = InventoryItem::where('status', 'active')->get();
        $farms = Farm::where('status', 'active')->get();
        return view('stock_movements.create', compact('items', 'farms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'farm_id'           => 'required|exists:farms,id',
            'movement_date'     => 'required|date',
            'movement_type'     => 'required|in:in,out,adjustment',
            'quantity'          => 'required|numeric|min:0.01',
            'unit_price'        => 'nullable|numeric|min:0',
            'reference_type'    => 'nullable|string|max:30',
            'reference_no'      => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
        ]);

        $item = InventoryItem::findOrFail($data['inventory_item_id']);
        $stockBefore = $item->current_stock;

        // ধরন অনুযায়ী নতুন স্টক হিসাব
        if ($data['movement_type'] === 'in') {
            $stockAfter = $stockBefore + $data['quantity'];
        } elseif ($data['movement_type'] === 'out') {
            if ($data['quantity'] > $stockBefore) {
                return back()->withErrors(['quantity' => 'Not enough stock! Current: ' . $stockBefore . ' ' . $item->unit])->withInput();
            }
            $stockAfter = $stockBefore - $data['quantity'];
        } else {
            // Adjustment: quantity হলো নতুন স্টক (absolute)
            $stockAfter = $data['quantity'];
        }

        $data['stock_before'] = $stockBefore;
        $data['stock_after']  = $stockAfter;
        $data['unit_price']   = $data['unit_price'] ?? 0;
        $data['total_value']  = $data['quantity'] * $data['unit_price'];
        $data['recorded_by']  = Auth::id();

        DB::transaction(function () use ($data, $item, $stockAfter) {
            StockMovement::create($data);
            $item->update(['current_stock' => $stockAfter]);
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement recorded! Stock updated: ' . $stockBefore . ' → ' . $stockAfter);
    }

    public function destroy(StockMovement $stockMovement)
    {
        return back()->withErrors(['error' => 'Stock movements cannot be deleted (ledger integrity). Use an adjustment entry instead.']);
    }
}