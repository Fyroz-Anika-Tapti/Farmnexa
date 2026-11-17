@extends('layouts.master')

@section('title', 'Add Farm')
@section('page-title', 'Add New Farm')
@section('breadcrumb', 'Farms / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Farm Details</h3>
    </div>
    <form action="{{ route('farms.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm Name <span class="text-danger">*</span></label>
                    <input type="text" name="farm_name" class="form-control @error('farm_name') is-invalid @enderror" value="{{ old('farm_name') }}" required>
                    @error('farm_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Farm Code <span class="text-danger">*</span></label>
                    <input type="text" name="farm_code" class="form-control @error('farm_code') is-invalid @enderror" value="{{ old('farm_code') }}" required>
                    @error('farm_code') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Currency Code <span class="text-danger">*</span></label>
                    <input type="text" name="currency_code" class="form-control" value="{{ old('currency_code', 'BDT') }}" maxlength="3" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Farm</button>
            <a href="{{ route('farms.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection