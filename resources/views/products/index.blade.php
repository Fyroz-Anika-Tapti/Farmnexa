@extends('layouts.master')

@section('title', 'Products')
@section('page-title', 'Products')
@section('breadcrumb', 'Sales / Products')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Products</h3>
        <div class="card-tools">
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($products->isEmpty())
            <div class="alert alert-info">No products yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Selling Price</th>
                        <th>Cost Price</th>
                        <th>Margin</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td><strong>{{ $p->product_code }}</strong></td>
                        <td>{{ $p->product_name }}</td>
                        <td><span class="badge badge-info">{{ $p->category }}</span></td>
                        <td>{{ $p->unit }}</td>
                        <td>{{ number_format($p->selling_price, 2) }} BDT</td>
                        <td>{{ number_format($p->cost_price, 2) }} BDT</td>
                        <td class="text-{{ $p->profit_margin > 0 ? 'success' : 'danger' }}">
                            {{ number_format($p->profit_margin, 2) }} BDT
                        </td>
                        <td>
                            <span class="badge badge-{{ $p->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $p) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $p) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this product?')">
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
            <div class="mt-3">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection