@extends('layouts.master')

@section('title', 'Add Section')
@section('page-title', 'Add New Section')
@section('breadcrumb', 'Sections / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Section Details</h3>
    </div>
    <form action="{{ route('sections.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control @error('farm_id') is-invalid @enderror" required>
                        <option value="">-- Select Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }} ({{ $farm->farm_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('farm_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Section Name <span class="text-danger">*</span></label>
                    <input type="text" name="section_name" class="form-control @error('section_name') is-invalid @enderror" value="{{ old('section_name') }}" required>
                    @error('section_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Section Type <span class="text-danger">*</span></label>
                    <select name="section_type" class="form-control @error('section_type') is-invalid @enderror" required>
                        <option value="">-- Select Type --</option>
                        <option value="CATTLE" {{ old('section_type') == 'CATTLE' ? 'selected' : '' }}>CATTLE</option>
                        <option value="DAIRY" {{ old('section_type') == 'DAIRY' ? 'selected' : '' }}>DAIRY</option>
                        <option value="POULTRY" {{ old('section_type') == 'POULTRY' ? 'selected' : '' }}>POULTRY</option>
                        <option value="SHARED" {{ old('section_type') == 'SHARED' ? 'selected' : '' }}>SHARED</option>
                    </select>
                    @error('section_type') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Section</button>
            <a href="{{ route('sections.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection