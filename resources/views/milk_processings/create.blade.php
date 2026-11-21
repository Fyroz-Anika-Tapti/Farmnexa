@extends('layouts.master')

@section('title', 'Add Milk Processing')
@section('page-title', 'Add Milk Processing')
@section('breadcrumb', 'Dairy / Milk Processing / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Milk Processing Details</h3>
    </div>
    <form action="{{ route('milk-processings.store') }}" method="POST">
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
                    <label>Section (Optional)</label>
                    <select name="section_id" class="form-control">
                        <option value="">-- Farm-wide --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Processing Date <span class="text-danger">*</span></label>
                    <input type="date" name="processing_date" class="form-control" value="{{ old('processing_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <select name="product_name" class="form-control" required>
                        <option value="">-- Select Product --</option>
                        <option value="Curd (দই)"    {{ old('product_name') == 'Curd (দই)' ? 'selected' : '' }}>Curd (দই)</option>
                        <option value="Ghee (ঘি)"    {{ old('product_name') == 'Ghee (ঘি)' ? 'selected' : '' }}>Ghee (ঘি)</option>
                        <option value="Paneer (পনির)" {{ old('product_name') == 'Paneer (পনির)' ? 'selected' : '' }}>Paneer (পনির)</option>
                        <option value="Butter (মাখন)" {{ old('product_name') == 'Butter (মাখন)' ? 'selected' : '' }}>Butter (মাখন)</option>
                        <option value="Sweet (মিষ্টি)" {{ old('product_name') == 'Sweet (মিষ্টি)' ? 'selected' : '' }}>Sweet (মিষ্টি)</option>
                        <option value="Other"         {{ old('product_name') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Input Milk (Liters) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="input_milk_liters" class="form-control" value="{{ old('input_milk_liters') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Output Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="output_quantity" class="form-control" value="{{ old('output_quantity') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Unit <span class="text-danger">*</span></label>
                    <select name="unit" class="form-control" required>
                        <option value="kg"     {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="liter"  {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                        <option value="piece"  {{ old('unit') == 'piece' ? 'selected' : '' }}>piece</option>
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Processing Cost (BDT)</label>
                    <input type="number" step="0.01" name="processing_cost" class="form-control" value="{{ old('processing_cost', '0.00') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Selling Price per Unit (BDT)</label>
                    <input type="number" step="0.01" name="selling_price_per_unit" class="form-control" value="{{ old('selling_price_per_unit') }}">
                </div>

                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Record</button>
            <a href="{{ route('milk-processings.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection