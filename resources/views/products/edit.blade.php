@extends('layouts.master')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('breadcrumb', 'Sales / Products / Edit')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit: {{ $product->product_name }}</h3></div>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Product Code <span class="text-danger">*</span></label>
                    <input type="text" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control" required>
                        @foreach(['Milk','Egg','Meat','Processed','Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Unit <span class="text-danger">*</span></label>
                    <select name="unit" class="form-control" required>
                        @foreach(['liter','piece','kg'] as $u)
                            <option value="{{ $u }}" {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Selling Price (BDT) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Cost Price (BDT)</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Current Stock</label>
                    <input type="number" step="0.01" name="current_stock" class="form-control" value="{{ old('current_stock', $product->current_stock ?? 0) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Reorder Level</label>
                    <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', $product->reorder_level) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection