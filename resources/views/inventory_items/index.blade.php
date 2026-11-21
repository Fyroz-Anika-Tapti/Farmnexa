@extends('layouts.master')

@section('title', 'Inventory Items')
@section('page-title', 'Inventory Items')
@section('breadcrumb', 'Inventory / Items')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Inventory Items</h3>
        <div class="card-tools">
            <a href="{{ route('inventory-items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Item
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($items->isEmpty())
            <div class="alert alert-info">No inventory items yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Current Stock</th>
                        <th>Reorder Level</th>
                        <th>Stock Value</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td><strong>{{ $item->item_code }}</strong></td>
                        <td>{{ $item->item_name }}</td>
                        <td><span class="badge badge-info">{{ $item->category }}</span></td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            {{ number_format($item->current_stock, 2) }}
                            @if($item->is_low_stock)
                                <span class="badge badge-danger">Low!</span>
                            @endif
                        </td>
                        <td>{{ number_format($item->reorder_level, 2) }}</td>
                        <td>{{ number_format($item->stock_value, 2) }} BDT</td>
                        <td>
                            <span class="badge badge-{{ $item->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('inventory-items.edit', $item) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('inventory-items.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $items->links() }}</div>
        @endif
    </div>
</div>
@endsection