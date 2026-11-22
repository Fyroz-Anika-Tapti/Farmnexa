@extends('layouts.master')

@section('title', 'PO Details')
@section('page-title', 'Purchase Order Details')
@section('breadcrumb', 'Procurement / Purchase Orders / View')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">PO: {{ $purchaseOrder->po_number }}</h3>
        <div class="card-tools">
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->supplier_name }}</p>
                <p><strong>Farm:</strong> {{ $purchaseOrder->farm->farm_name }}</p>
                <p><strong>PO Date:</strong> {{ $purchaseOrder->po_date->format('d M, Y') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}</span></p>
                <p><strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery_date?->format('d M, Y') ?? '-' }}</p>
                <p><strong>Created By:</strong> {{ $purchaseOrder->createdBy->name ?? '-' }}</p>
            </div>
        </div>

        <hr>
        <h5>Items</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                    <th>Received</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->lines as $line)
                <tr>
                    <td>{{ $line->item->item_name }} ({{ $line->item->item_code }})</td>
                    <td>{{ number_format($line->quantity, 2) }}</td>
                    <td>{{ number_format($line->unit_price, 2) }} BDT</td>
                    <td>{{ number_format($line->line_total, 2) }} BDT</td>
                    <td>{{ number_format($line->received_quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-md-4 offset-md-8">
                <table class="table">
                    <tr><td>Subtotal:</td><td>{{ number_format($purchaseOrder->subtotal, 2) }} BDT</td></tr>
                    <tr><td>Tax:</td><td>{{ number_format($purchaseOrder->tax_amount, 2) }} BDT</td></tr>
                    <tr><td>Discount:</td><td>{{ number_format($purchaseOrder->discount_amount, 2) }} BDT</td></tr>
                    <tr><td><strong>Total:</strong></td><td><strong>{{ number_format($purchaseOrder->total_amount, 2) }} BDT</strong></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection