<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Farm;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = InventoryItem::with('farm')->latest()->paginate(15);
        return view('inventory_items.index', compact('items'));
    }

    public function create()
    {
        $farms = Farm::where('status', 'active')->get();
        return view('inventory_items.create', compact('farms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'        => 'required|exists:farms,id',
            'item_code'      => 'required|string|max:50|unique:inventory_items,item_code',
            'item_name'      => 'required|string|max:150',
            'category'       => 'required|string|max:50',
            'unit'           => 'required|string|max:20',
            'opening_stock'  => 'required|numeric|min:0',
            'reorder_level'  => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|string|max:30',
        ]);

        // Opening stock = Current stock (প্রাথমিক)
        $data['current_stock'] = $data['opening_stock'];

        InventoryItem::create($data);

        return redirect()->route('inventory-items.index')
            ->with('success', 'Item created successfully!');
    }

    public function edit(InventoryItem $inventoryItem)
    {
        $farms = Farm::where('status', 'active')->get();
        return view('inventory_items.edit', compact('inventoryItem', 'farms'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $data = $request->validate([
            'farm_id'        => 'required|exists:farms,id',
            'item_code'      => 'required|string|max:50|unique:inventory_items,item_code,' . $inventoryItem->id,
            'item_name'      => 'required|string|max:150',
            'category'       => 'required|string|max:50',
            'unit'           => 'required|string|max:20',
            'reorder_level'  => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|string|max:30',
        ]);

        // Opening stock update করা যাবে না। Current stock stock-movement দিয়ে পরিবর্তন হবে।
        $inventoryItem->update($data);

        return redirect()->route('inventory-items.index')
            ->with('success', 'Item updated successfully!');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();
        return redirect()->route('inventory-items.index')
            ->with('success', 'Item deleted successfully!');
    }
}