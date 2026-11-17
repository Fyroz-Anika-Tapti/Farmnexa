@extends('layouts.master')

@section('title', 'Edit Cost Centre')
@section('page-title', 'Edit Cost Centre')
@section('breadcrumb', 'Cost Centres / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $costCentre->centre_name }}</h3>
    </div>
    <form action="{{ route('cost-centres.update', $costCentre) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id', $costCentre->farm_id) == $farm->id ? 'selected' : '' }}>
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
                            <option value="{{ $section->id }}" {{ old('section_id', $costCentre->section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Centre Code <span class="text-danger">*</span></label>
                    <input type="text" name="centre_code" class="form-control" value="{{ old('centre_code', $costCentre->centre_code) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Centre Name <span class="text-danger">*</span></label>
                    <input type="text" name="centre_name" class="form-control" value="{{ old('centre_name', $costCentre->centre_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $costCentre->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $costCentre->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $costCentre->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Cost Centre</button>
            <a href="{{ route('cost-centres.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection