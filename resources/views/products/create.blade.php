@extends('layouts.master')

@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('breadcrumb', 'Sales / Products / Create')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Product Details</h3></div>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Product Code <span class="text-danger">*</span></label>
                    <input type="text" name="product_code" class="form-control" value="{{ old('product_code', 'PRD-' . date('YmdHis')) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" placeholder="e.g. Fresh Milk" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="Milk"      {{ old('category') == 'Milk' ? 'selected' : '' }}>Milk</option>
                        <option value="Egg"       {{ old('category') == 'Egg' ? 'selected' : '' }}>Egg</option>
                        <option value="Meat"      {{ old('category') == 'Meat' ? 'selected' : '' }}>Meat</option>
                        <option value="Processed" {{ old('category') == 'Processed' ? 'selected' : '' }}>Processed</option>
                        <option value="Other"     {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Unit <span class="text-danger">*</span></label>
                    <select name="unit" class="form-control" required>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>piece</option>
                        <option value="kg"    {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Selling Price (BDT) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Cost Price (BDT)</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', '0.00') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Current Stock</label>
                    <input type="number" step="0.01" name="current_stock" class="form-control" value="{{ old('current_stock', $product->current_stock ?? 0) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Reorder Level</label>
                    <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', 0) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection