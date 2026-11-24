@extends('layouts.master')

@section('title', 'Sales Invoices')
@section('page-title', 'Sales Invoices')
@section('breadcrumb', 'Sales / Invoices')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Sales Invoices</h3>
        <div class="card-tools">
            <a href="{{ route('sales-invoices.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Invoice
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($invoices->isEmpty())
            <div class="alert alert-info">No invoices yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th width="120px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td><strong>{{ $inv->invoice_number }}</strong></td>
                        <td>{{ $inv->invoice_date->format('d M, Y') }}</td>
                        <td>{{ $inv->customer->customer_name ?? 'N/A' }}</td>
                        <td>{{ number_format($inv->total_amount, 2) }} BDT</td>
                        <td class="text-success">{{ number_format($inv->paid_amount, 2) }} BDT</td>
                        <td class="text-danger">{{ number_format($inv->due_amount, 2) }} BDT</td>
                        <td>
                            @php
                                $badge = ['unpaid' => 'danger', 'partial' => 'warning', 'paid' => 'success'][$inv->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst($inv->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('sales-invoices.show', $inv) }}" class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($inv->paid_amount == 0)
                            <form action="{{ route('sales-invoices.destroy', $inv) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this invoice?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $invoices->links() }}</div>
        @endif
    </div>
</div>
@endsection