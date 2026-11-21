@extends('layouts.master')

@section('title', 'Add Inventory Item')
@section('page-title', 'Add Inventory Item')
@section('breadcrumb', 'Inventory / Items / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Item Details</h3>
    </div>
    <form action="{{ route('inventory-items.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        <option value="">-- Select Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Item Code <span class="text-danger">*</span></label>
                    <input type="text" name="item_code" class="form-control" value="{{ old('item_code') }}" placeholder="e.g. FEED-001" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control" value="{{ old('item_name') }}" placeholder="e.g. Cattle Feed" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="Feed"      {{ old('category') == 'Feed' ? 'selected' : '' }}>Feed (খাদ্য)</option>
                        <option value="Medicine"  {{ old('category') == 'Medicine' ? 'selected' : '' }}>Medicine (ঔষধ)</option>
                        <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>Equipment (যন্ত্রপাতি)</option>
                        <option value="Other"     {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Unit <span class="text-danger">*</span></label>
                    <select name="unit" class="form-control" required>
                        <option value="kg"     {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="liter"  {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                        <option value="piece"  {{ old('unit') == 'piece' ? 'selected' : '' }}>piece</option>
                        <option value="bag"    {{ old('unit') == 'bag' ? 'selected' : '' }}>bag</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Opening Stock <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="opening_stock" class="form-control" value="{{ old('opening_stock', '0.00') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Reorder Level <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="reorder_level" class="form-control" value="{{ old('reorder_level', '10.00') }}" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Purchase Price (per unit) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', '0.00') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Item</button>
            <a href="{{ route('inventory-items.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection