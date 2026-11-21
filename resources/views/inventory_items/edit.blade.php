@extends('layouts.master')

@section('title', 'Edit Inventory Item')
@section('page-title', 'Edit Inventory Item')
@section('breadcrumb', 'Inventory / Items / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $inventoryItem->item_name }}</h3>
    </div>
    <form action="{{ route('inventory-items.update', $inventoryItem) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id', $inventoryItem->farm_id) == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Item Code <span class="text-danger">*</span></label>
                    <input type="text" name="item_code" class="form-control" value="{{ old('item_code', $inventoryItem->item_code) }}" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $inventoryItem->item_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control" required>
                        @foreach(['Feed','Medicine','Equipment','Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $inventoryItem->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Unit <span class="text-danger">*</span></label>
                    <select name="unit" class="form-control" required>
                        @foreach(['kg','liter','piece','bag'] as $u)
                            <option value="{{ $u }}" {{ old('unit', $inventoryItem->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Current Stock (auto)</label>
                    <input type="text" class="form-control" value="{{ number_format($inventoryItem->current_stock, 2) }} {{ $inventoryItem->unit }}" disabled>
                </div>
                <div class="col-md-4 form-group">
                    <label>Reorder Level <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="reorder_level" class="form-control" value="{{ old('reorder_level', $inventoryItem->reorder_level) }}" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Purchase Price (per unit) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $inventoryItem->purchase_price) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status', $inventoryItem->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $inventoryItem->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $inventoryItem->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Item</button>
            <a href="{{ route('inventory-items.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection