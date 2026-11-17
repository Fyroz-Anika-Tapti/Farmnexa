@extends('layouts.master')

@section('title', 'Edit Farm')
@section('page-title', 'Edit Farm')
@section('breadcrumb', 'Farms / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $farm->farm_name }}</h3>
    </div>
    <form action="{{ route('farms.update', $farm) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm Name <span class="text-danger">*</span></label>
                    <input type="text" name="farm_name" class="form-control" value="{{ old('farm_name', $farm->farm_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Farm Code <span class="text-danger">*</span></label>
                    <input type="text" name="farm_code" class="form-control" value="{{ old('farm_code', $farm->farm_code) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $farm->location) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $farm->phone) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Currency Code <span class="text-danger">*</span></label>
                    <input type="text" name="currency_code" class="form-control" value="{{ old('currency_code', $farm->currency_code) }}" maxlength="3" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $farm->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $farm->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Farm</button>
            <a href="{{ route('farms.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection