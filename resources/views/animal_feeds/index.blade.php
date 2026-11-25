@extends('layouts.master')

@section('title', 'Feed Records')
@section('page-title', 'Feed Records')
@section('breadcrumb', 'Animals / Feed Records')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-utensils"></i> Feed History: {{ $animal->animal_code }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('animals.feeds.create', $animal) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Feed
            </a>
            <a href="{{ route('animals.show', $animal) }}" class="btn btn-default btn-sm ml-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($records->isEmpty())
            <div class="alert alert-info">No feed records yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total Cost</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalQty = 0; $totalCost = 0; @endphp
                    @foreach($records as $rec)
                        @php $totalQty += $rec->quantity; $totalCost += $rec->total_cost; @endphp
                        <tr>
                            <td>{{ $rec->feed_date->format('d M, Y') }}</td>
                            <td>
                                <strong>{{ $rec->inventoryItem->item_code ?? 'N/A' }}</strong><br>
                                <small>{{ $rec->inventoryItem->item_name ?? '' }}</small>
                            </td>
                            <td class="text-right">{{ number_format($rec->quantity, 2) }} {{ $rec->unit }}</td>
                            <td class="text-right">{{ number_format($rec->unit_price, 2) }} BDT</td>
                            <td class="text-right"><strong>{{ number_format($rec->total_cost, 2) }} BDT</strong></td>
                            <td>
                                <form action="{{ route('animals.feeds.destroy', $rec) }}" method="POST" onsubmit="return confirm('Delete and restore stock?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light">
                        <td colspan="2"><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalQty, 2) }}</strong></td>
                        <td></td>
                        <td class="text-right"><strong>{{ number_format($totalCost, 2) }} BDT</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="mt-3">{{ $records->links() }}</div>
        @endif
    </div>
</div>
@endsection