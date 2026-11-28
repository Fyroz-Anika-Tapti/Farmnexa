<?php

namespace App\Http\Controllers;

use App\Models\MilkProcessing;
use App\Models\Farm;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class MilkProcessingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = MilkProcessing::with(['farm', 'section']);

        // Filters
        if ($request->filled('farm_id')) {
            $query->where('farm_id', $request->farm_id);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        if ($request->filled('from_date')) {
            $query->where('processing_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('processing_date', '<=', $request->to_date);
        }

        $processings = $query->latest('processing_date')->latest('id')->paginate(20);

        // Summary
        $summary = MilkProcessing::query();
        if ($request->filled('farm_id')) $summary->where('farm_id', $request->farm_id);
        if ($request->filled('section_id')) $summary->where('section_id', $request->section_id);
        if ($request->filled('product_name')) $summary->where('product_name', 'like', '%' . $request->product_name . '%');
        if ($request->filled('from_date')) $summary->where('processing_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $summary->where('processing_date', '<=', $request->to_date);

        $totals = [
            'input_milk'   => $summary->sum('input_milk_liters'),
            'output'       => $summary->sum('output_quantity'),
            'cost'         => $summary->sum('processing_cost'),
            'revenue'      => $summary->get()->sum(function ($p) {
                return $p->total_value;
            }),
        ];

        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')
            ->whereIn('section_type', ['DAIRY', 'SHARED'])
            ->get();

        return view('milk_processings.index', compact('processings', 'totals', 'farms', 'sections'));
    }

    public function create()
{
    $farms = Farm::where('status', 'active')->get();
    $sections = Section::where('status', 'active')
        ->whereIn('section_type', ['DAIRY', 'SHARED'])
        ->get();

    // Today & week stats
    $todayBatches = MilkProcessing::where('processing_date', today())->count();
    $weekBatches  = MilkProcessing::whereBetween('processing_date', [
        today()->subDays(6), today()
    ])->count();

    $todayMilkIn = MilkProcessing::where('processing_date', today())->sum('input_milk_liters');
    $weekMilkIn  = MilkProcessing::whereBetween('processing_date', [
        today()->subDays(6), today()
    ])->sum('input_milk_liters');

    // Previous batches (last 5)
    $previousBatches = MilkProcessing::latest('processing_date')->latest('id')->take(5)->get();

    // 👇 নতুন — Raw Milk stock & average cost
    $rawMilk = InventoryItem::where('item_code', 'MILK-RAW-001')->first();
    $availableMilk = $rawMilk ? (float) $rawMilk->current_stock : 0;
    $avgMilkCost   = $rawMilk ? (float) $rawMilk->average_cost : 60;
    $avgMilkPrice  = $avgMilkCost; // backward compatibility

    return view('milk_processings.create', compact(
        'farms', 'sections',
        'todayBatches', 'weekBatches',
        'todayMilkIn', 'weekMilkIn',
        'avgMilkPrice',
        'previousBatches',
        'availableMilk',  // 👈 নতুন
        'avgMilkCost'     // 👈 নতুন
    ));
}
    public function store(Request $request)
{
    $data = $request->validate([
        'farm_id'                => 'required|exists:farms,id',
        'section_id'             => 'nullable|exists:sections,id',
        'processing_date'        => 'required|date',
        'product_name'           => 'required|string|max:100',
        'input_milk_liters'      => 'required|numeric|min:0.01',
        'output_quantity'        => 'required|numeric|min:0.01',
        'unit'                   => 'required|string|max:20',
        'processing_cost'        => 'nullable|numeric|min:0',
        'selling_price_per_unit' => 'nullable|numeric|min:0',
        'notes'                  => 'nullable|string',
    ]);

    $rawMilk = InventoryItem::where('item_code', 'MILK-RAW-001')->first();

    if (!$rawMilk) {
        return back()->withErrors(['error' => 'Raw Milk inventory item not found.'])->withInput();
    }

    if ($rawMilk->current_stock < $data['input_milk_liters']) {
        return back()->withErrors([
            'input_milk_liters' => "Not enough Raw Milk! Available: {$rawMilk->current_stock} L"
        ])->withInput();
    }

    try {
        DB::transaction(function () use ($data, $rawMilk) {
            // Milk cost = input × average cost
            $milkCost = $data['input_milk_liters'] * $rawMilk->average_cost;
            $data['milk_cost']       = $milkCost;
            $data['processing_cost'] = $data['processing_cost'] ?? 0;
            $data['recorded_by']     = Auth::id();

            MilkProcessing::create($data);

            // Raw Milk Out
            $stockBefore = $rawMilk->current_stock;
            $avgCost     = $rawMilk->average_cost;

            $rawMilk->removeStock($data['input_milk_liters']);

            StockMovement::create([
                'inventory_item_id' => $rawMilk->id,
                'farm_id'           => $data['farm_id'],
                'movement_date'     => $data['processing_date'],
                'movement_type'     => 'out',
                'quantity'          => $data['input_milk_liters'],
                'unit_price'        => $avgCost,
                'total_value'       => $data['input_milk_liters'] * $avgCost,
                'stock_before'      => $stockBefore,
                'stock_after'       => $rawMilk->fresh()->current_stock,
                'reference_type'    => 'milk_processing',
                'reference_no'      => 'PRC-' . date('YmdHis'),
                'notes'             => 'Processed into ' . $data['product_name'],
                'recorded_by'       => Auth::id(),
            ]);
        });
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }

    return redirect()->route('milk-processings.create')
        ->with('success', 'Saved! Raw Milk -' . number_format($data['input_milk_liters'], 2) . ' L');
}

    public function destroy(MilkProcessing $milkProcessing)
    {
        $milkProcessing->delete();
        return redirect()->route('milk-processings.index')
            ->with('success', 'Processing record deleted!');
    }
}