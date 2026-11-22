<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Supplier;
use App\Models\Farm;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'farm'])->latest('po_date')->paginate(15);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $farms      = Farm::where('status', 'active')->get();
        $suppliers  = Supplier::where('status', 'active')->get();
        $items      = InventoryItem::where('status', 'active')->get();
        return view('purchase_orders.create', compact('farms', 'suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'                 => 'required|exists:farms,id',
            'supplier_id'             => 'required|exists:suppliers,id',
            'po_number'               => 'required|string|max:50|unique:purchase_orders,po_number',
            'po_date'                 => 'required|date',
            'expected_delivery_date'  => 'nullable|date|after_or_equal:po_date',
            'tax_amount'              => 'nullable|numeric|min:0',
            'discount_amount'         => 'nullable|numeric|min:0',
            'notes'                   => 'nullable|string',
            'items'                   => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity'        => 'required|numeric|min:0.01',
            'items.*.unit_price'      => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax      = $data['tax_amount'] ?? 0;
            $discount = $data['discount_amount'] ?? 0;
            $total    = $subtotal + $tax - $discount;

            $po = PurchaseOrder::create([
                'farm_id'                => $data['farm_id'],
                'supplier_id'            => $data['supplier_id'],
                'po_number'              => $data['po_number'],
                'po_date'                => $data['po_date'],
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'subtotal'               => $subtotal,
                'tax_amount'             => $tax,
                'discount_amount'        => $discount,
                'total_amount'           => $total,
                'status'                 => 'pending',
                'notes'                  => $data['notes'] ?? null,
                'created_by'             => Auth::id(),
            ]);

            foreach ($data['items'] as $item) {
                PurchaseOrderLine::create([
                    'purchase_order_id' => $po->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'quantity'          => $item['quantity'],
                    'unit_price'        => $item['unit_price'],
                    'line_total'        => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order created successfully!');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'farm', 'lines.item', 'createdBy']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function receiveForm(PurchaseOrder $purchaseOrder)
{
    $purchaseOrder->load(['lines.item', 'supplier', 'farm']);

    if (in_array($purchaseOrder->status, ['received', 'cancelled'])) {
        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->withErrors(['error' => 'This PO is already fully received or cancelled.']);
    }

    $lastGr = \App\Models\GoodsReceipt::orderBy('id', 'desc')->first();
    $grNumber = 'GR-' . date('Ymd') . '-' . str_pad(($lastGr ? $lastGr->id + 1 : 1), 4, '0', STR_PAD_LEFT);

    return view('purchase_orders.receive', compact('purchaseOrder', 'grNumber'));
}

public function receiveStore(Request $request, PurchaseOrder $purchaseOrder)
{
    $data = $request->validate([
        'gr_number'            => 'required|string|max:50|unique:goods_receipts,gr_number',
        'received_date'        => 'required|date',
        'notes'                => 'nullable|string',
        'items'                => 'required|array|min:1',
        'items.*.purchase_order_line_id' => 'required|exists:purchase_order_lines,id',
        'items.*.received_quantity'      => 'required|numeric|min:0',
    ]);

    $totalReceived = array_sum(array_column($data['items'], 'received_quantity'));
    if ($totalReceived <= 0) {
        return back()->withErrors(['items' => 'You must receive at least one item.'])->withInput();
    }

    \DB::transaction(function () use ($data, $purchaseOrder) {
        $gr = \App\Models\GoodsReceipt::create([
            'purchase_order_id' => $purchaseOrder->id,
            'farm_id'           => $purchaseOrder->farm_id,
            'supplier_id'       => $purchaseOrder->supplier_id,
            'gr_number'         => $data['gr_number'],
            'received_date'     => $data['received_date'],
            'notes'             => $data['notes'] ?? null,
            'received_by'       => auth()->id(),
        ]);

        foreach ($data['items'] as $itemData) {
            $receivedQty = (float) $itemData['received_quantity'];
            if ($receivedQty <= 0) continue;

            $poLine = \App\Models\PurchaseOrderLine::findOrFail($itemData['purchase_order_line_id']);

            \App\Models\GoodsReceiptLine::create([
                'goods_receipt_id'        => $gr->id,
                'purchase_order_line_id'  => $poLine->id,
                'inventory_item_id'       => $poLine->inventory_item_id,
                'received_quantity'       => $receivedQty,
                'unit_price'              => $poLine->unit_price,
                'line_total'              => $receivedQty * $poLine->unit_price,
            ]);

            $poLine->received_quantity += $receivedQty;
            $poLine->save();

            $inventoryItem = \App\Models\InventoryItem::findOrFail($poLine->inventory_item_id);
            $stockBefore = $inventoryItem->current_stock;
            $stockAfter  = $stockBefore + $receivedQty;

            \App\Models\StockMovement::create([
                'inventory_item_id' => $inventoryItem->id,
                'farm_id'           => $purchaseOrder->farm_id,
                'movement_date'     => $data['received_date'],
                'movement_type'     => 'in',
                'quantity'          => $receivedQty,
                'unit_price'        => $poLine->unit_price,
                'total_value'       => $receivedQty * $poLine->unit_price,
                'stock_before'      => $stockBefore,
                'stock_after'       => $stockAfter,
                'reference_type'    => 'goods_receipt',
                'reference_no'      => $gr->gr_number,
                'notes'             => 'Auto-created from Goods Receipt',
                'recorded_by'       => auth()->id(),
            ]);

            $inventoryItem->update(['current_stock' => $stockAfter]);
        }

        $allLines = $purchaseOrder->lines()->get();
        $fullyReceived = true;
        foreach ($allLines as $line) {
            if ($line->received_quantity < $line->quantity) {
                $fullyReceived = false;
                break;
            }
        }

        $purchaseOrder->status = $fullyReceived ? 'received' : 'partially_received';
        $purchaseOrder->save();
    });

    return redirect()->route('purchase-orders.show', $purchaseOrder)
        ->with('success', 'Goods received successfully! Stock has been updated.');
}

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending POs can be deleted.']);
        }

        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order deleted successfully!');
    }
}