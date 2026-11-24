@extends('layouts.master')

@section('title', 'Invoice Details')
@section('page-title', 'Sales Invoice')
@section('breadcrumb', 'Sales / Invoices / View')

@section('content')
<style>
    @media print {
        .main-header, .main-sidebar, .main-footer, .content-header, .card-tools, .card-header { display: none !important; }
        .content-wrapper { margin: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { background: white !important; }
        .container-fluid { padding: 0 !important; }
    }
</style>
<div class="card">
    <div class="card-header">
    <h3 class="card-title">Invoice: {{ $salesInvoice->invoice_number }}</h3>
    <div class="card-tools">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print"></i> Print
        </button>
        <a href="{{ route('sales-invoices.index') }}" class="btn btn-default btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Customer:</strong> {{ $salesInvoice->customer->customer_name }}</p>
                <p><strong>Farm:</strong> {{ $salesInvoice->farm->farm_name }}</p>
                <p><strong>Invoice Date:</strong> {{ $salesInvoice->invoice_date->format('d M, Y') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong>
                    <span class="badge badge-{{ $salesInvoice->status === 'paid' ? 'success' : ($salesInvoice->status === 'partial' ? 'warning' : 'danger') }}">
                        {{ ucfirst($salesInvoice->status) }}
                    </span>
                </p>
                <p><strong>Due Date:</strong> {{ $salesInvoice->due_date?->format('d M, Y') ?? '-' }}</p>
                <p><strong>Created By:</strong> {{ $salesInvoice->createdBy->name ?? '-' }}</p>
            </div>
        </div>
        <hr>
        <h5>Items</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesInvoice->lines as $line)
                <tr>
                    <td>{{ $line->product->product_name }}</td>
                    <td>{{ number_format($line->quantity, 2) }} {{ $line->product->unit }}</td>
                    <td>{{ number_format($line->unit_price, 2) }} BDT</td>
                    <td>{{ number_format($line->line_total, 2) }} BDT</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row mt-3">
            <div class="col-md-4 offset-md-8">
                <table class="table">
                    <tr><td>Subtotal:</td><td>{{ number_format($salesInvoice->subtotal, 2) }} BDT</td></tr>
                    <tr><td>Tax:</td><td>{{ number_format($salesInvoice->tax_amount, 2) }} BDT</td></tr>
                    <tr><td>Discount:</td><td>{{ number_format($salesInvoice->discount_amount, 2) }} BDT</td></tr>
                    <tr><td><strong>Total:</strong></td><td><strong>{{ number_format($salesInvoice->total_amount, 2) }} BDT</strong></td></tr>
                    <tr><td>Paid:</td><td class="text-success">{{ number_format($salesInvoice->paid_amount, 2) }} BDT</td></tr>
                    <tr><td><strong>Due:</strong></td><td class="text-danger"><strong>{{ number_format($salesInvoice->due_amount, 2) }} BDT</strong></td></tr>
                </table>
            </div>
        </div>
    </div>
                <hr>
            <h5><i class="fas fa-money-bill-wave"></i> Payment History</h5>
            @if($salesInvoice->payments->isEmpty())
                <p class="text-muted">No payments yet.</p>
            @else
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Payment #</th>
                            <th>Date</th>
                            <th class="text-right">Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesInvoice->payments as $pay)
                        <tr>
                            <td>{{ $pay->payment_number }}</td>
                            <td>{{ $pay->payment_date->format('d M, Y') }}</td>
                            <td class="text-right">{{ number_format($pay->amount, 2) }} BDT</td>
                            <td><span class="badge badge-info">{{ ucfirst($pay->payment_method) }}</span></td>
                            <td>{{ $pay->reference_no ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
</div>
@endsection