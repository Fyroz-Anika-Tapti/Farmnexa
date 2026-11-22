@extends('layouts.master')

@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')
@section('breadcrumb', 'Procurement / Purchase Orders')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Purchase Orders</h3>
        <div class="card-tools">
            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New PO
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($orders->isEmpty())
            <div class="alert alert-info">No purchase orders yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>PO #</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $po)
                    <tr>
                        <td><strong>{{ $po->po_number }}</strong></td>
                        <td>{{ $po->po_date->format('d M, Y') }}</td>
                        <td>{{ $po->supplier->supplier_name ?? 'N/A' }}</td>
                        <td>{{ number_format($po->total_amount, 2) }} BDT</td>
                        <td>
                            @php
                                $badge = [
                                    'pending' => 'warning',
                                    'approved' => 'info',
                                    'partially_received' => 'primary',
                                    'received' => 'success',
                                    'cancelled' => 'secondary',
                                ][$po->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $po->status)) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if(in_array($po->status, ['pending', 'partially_received']))
                            <a href="{{ route('purchase-orders.receive', $po) }}" class="btn btn-warning btn-sm" title="Receive Goods">
                                <i class="fas fa-box-open"></i>
                            </a>
                            @endif

                            @if($po->status === 'pending')
                            <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this PO?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection