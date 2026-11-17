@extends('layouts.master')

@section('title', 'Add Cost Centre')
@section('page-title', 'Add New Cost Centre')
@section('breadcrumb', 'Cost Centres / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cost Centre Details</h3>
    </div>
    <form action="{{ route('cost-centres.store') }}" method="POST">
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
                        <option value="">-- Farm-wide (No Section) --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Centre Code <span class="text-danger">*</span></label>
                    <input type="text" name="centre_code" class="form-control @error('centre_code') is-invalid @enderror" value="{{ old('centre_code') }}" placeholder="e.g. CC-001" required>
                    @error('centre_code') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Centre Name <span class="text-danger">*</span></label>
                    <input type="text" name="centre_name" class="form-control @error('centre_name') is-invalid @enderror" value="{{ old('centre_name') }}" placeholder="e.g. Dairy Shed A" required>
                    @error('centre_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
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
            <button type="submit" class="btn btn-primary">Save Cost Centre</button>
            <a href="{{ route('cost-centres.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection