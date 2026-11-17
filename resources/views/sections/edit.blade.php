@extends('layouts.master')

@section('title', 'Edit Section')
@section('page-title', 'Edit Section')
@section('breadcrumb', 'Sections / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $section->section_name }}</h3>
    </div>
    <form action="{{ route('sections.update', $section) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id', $section->farm_id) == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }} ({{ $farm->farm_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Section Name <span class="text-danger">*</span></label>
                    <input type="text" name="section_name" class="form-control" value="{{ old('section_name', $section->section_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Section Type <span class="text-danger">*</span></label>
                    <select name="section_type" class="form-control" required>
                        <option value="CATTLE" {{ old('section_type', $section->section_type) == 'CATTLE' ? 'selected' : '' }}>CATTLE</option>
                        <option value="DAIRY" {{ old('section_type', $section->section_type) == 'DAIRY' ? 'selected' : '' }}>DAIRY</option>
                        <option value="POULTRY" {{ old('section_type', $section->section_type) == 'POULTRY' ? 'selected' : '' }}>POULTRY</option>
                        <option value="SHARED" {{ old('section_type', $section->section_type) == 'SHARED' ? 'selected' : '' }}>SHARED</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $section->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $section->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $section->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Section</button>
            <a href="{{ route('sections.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection