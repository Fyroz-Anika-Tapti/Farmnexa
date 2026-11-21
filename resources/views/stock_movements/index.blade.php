@extends('layouts.master')

@section('title', 'Stock Movements')
@section('page-title', 'Stock Movements')
@section('breadcrumb', 'Inventory / Stock Movements')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Stock Movements (Ledger)</h3>
        <div class="card-tools">
            <a href="{{ route('stock-movements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Movement
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($movements->isEmpty())
            <div class="alert alert-info">No stock movements yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Stock Before → After</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $mv)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($mv->movement_date)->format('d M, Y') }}</td>
                        <td>
                            <strong>{{ $mv->item->item_code ?? 'N/A' }}</strong><br>
                            <small>{{ $mv->item->item_name ?? '' }}</small>
                        </td>
                        <td>
                            @if($mv->movement_type === 'in')
                                <span class="badge badge-success"><i class="fas fa-arrow-down"></i> In</span>
                            @elseif($mv->movement_type === 'out')
                                <span class="badge badge-danger"><i class="fas fa-arrow-up"></i> Out</span>
                            @else
                                <span class="badge badge-warning"><i class="fas fa-balance-scale"></i> Adjustment</span>
                            @endif
                        </td>
                        <td>{{ number_format($mv->quantity, 2) }} {{ $mv->item->unit ?? '' }}</td>
                        <td>{{ number_format($mv->unit_price, 2) }} BDT</td>
                        <td>{{ number_format($mv->total_value, 2) }} BDT</td>
                        <td>
                            <small class="text-muted">{{ number_format($mv->stock_before, 2) }}</small>
                            <strong>→</strong>
                            <strong>{{ number_format($mv->stock_after, 2) }}</strong>
                        </td>
                        <td>
                            @if($mv->reference_no)
                                <small>{{ $mv->reference_type }}: {{ $mv->reference_no }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection