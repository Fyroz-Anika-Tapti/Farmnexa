<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceLine;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerPayment;

class SalesInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $invoices = SalesInvoice::with(['customer', 'farm'])
            ->latest('invoice_date')->paginate(15);
        return view('sales_invoices.index', compact('invoices'));
    }

    public function create()
    {
        $farms     = Farm::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();
        $products  = Product::where('status', 'active')->get();

        $lastInvoice = SalesInvoice::orderBy('id', 'desc')->first();
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('sales_invoices.create', compact('farms', 'customers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'          => 'required|exists:farms,id',
            'customer_id'      => 'required|exists:customers,id',
            'invoice_number'   => 'required|string|max:50|unique:sales_invoices,invoice_number',
            'invoice_date'     => 'required|date',
            'due_date'         => 'nullable|date|after_or_equal:invoice_date',
            'tax_amount'       => 'nullable|numeric|min:0',
            'discount_amount'  => 'nullable|numeric|min:0',
            'paid_amount'      => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method'    => 'nullable|string|max:30',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax      = $data['tax_amount'] ?? 0;
            $discount = $data['discount_amount'] ?? 0;
            $total    = $subtotal + $tax - $discount;
            $paid     = $data['paid_amount'] ?? 0;
            $due      = $total - $paid;

            $status = 'unpaid';
            if ($paid >= $total) $status = 'paid';
            elseif ($paid > 0)   $status = 'partial';

            $invoice = SalesInvoice::create([
                'farm_id'         => $data['farm_id'],
                'customer_id'     => $data['customer_id'],
                'invoice_number'  => $data['invoice_number'],
                'invoice_date'    => $data['invoice_date'],
                'due_date'        => $data['due_date'] ?? null,
                'subtotal'        => $subtotal,
                'tax_amount'      => $tax,
                'discount_amount' => $discount,
                'total_amount'    => $total,
                'paid_amount'     => $paid,
                'due_amount'      => $due,
                'status'          => $status,
                'notes'           => $data['notes'] ?? null,
                'created_by'      => Auth::id(),
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                SalesInvoiceLine::create([
                    'sales_invoice_id' => $invoice->id,
                    'product_id'       => $product->id,
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $item['unit_price'],
                    'line_total'       => $item['quantity'] * $item['unit_price'],
                    'cost_price'       => $product->cost_price,
                ]);
            }

            // 👇 Payment record তৈরি (যদি paid > 0)
            if ($paid > 0) {
                $lastPay = CustomerPayment::orderBy('id', 'desc')->first();
                $payNum = 'PAY-' . date('Ymd') . '-' . str_pad(($lastPay ? $lastPay->id + 1 : 1), 4, '0', STR_PAD_LEFT);

                CustomerPayment::create([
                    'customer_id'      => $data['customer_id'],
                    'sales_invoice_id' => $invoice->id,
                    'payment_number'   => $payNum,
                    'payment_date'     => $data['invoice_date'],
                    'amount'           => $paid,
                    'payment_method'   => $data['payment_method'] ?? 'cash',
                    'reference_no'     => $data['payment_reference'] ?? null,
                    'notes'            => 'Auto from Invoice ' . $invoice->invoice_number,
                ]);
            }

            // Customer balance আপডেট
            $customer = Customer::find($data['customer_id']);
            if ($customer && $due > 0) {
                $customer->current_balance += $due;
                $customer->save();
            }
        });

        return redirect()->route('sales-invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(SalesInvoice $salesInvoice)
    {
        $salesInvoice->load(['customer', 'farm', 'lines.product', 'createdBy']);
        return view('sales_invoices.show', compact('salesInvoice'));
    }

    public function destroy(SalesInvoice $salesInvoice)
    {
        if ($salesInvoice->paid_amount > 0) {
            return back()->withErrors(['error' => 'Cannot delete an invoice with payments.']);
        }

        DB::transaction(function () use ($salesInvoice) {
            $customer = $salesInvoice->customer;
            if ($customer) {
                $customer->current_balance -= $salesInvoice->due_amount;
                $customer->save();
            }
            $salesInvoice->delete();
        });

        return redirect()->route('sales-invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }
}