@extends('layouts.master')

@section('title', 'Edit Poultry Batch')
@section('page-title', 'Edit Poultry Batch')
@section('breadcrumb', 'Poultry / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit: {{ $poultryBatch->batch_code }}</h3>
    </div>
    <form action="{{ route('poultry-batches.update', $poultryBatch) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id', $poultryBatch->farm_id) == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Section <span class="text-danger">*</span></label>
                    <select name="section_id" class="form-control" required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id', $poultryBatch->section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Batch Code <span class="text-danger">*</span></label>
                    <input type="text" name="batch_code" class="form-control" value="{{ old('batch_code', $poultryBatch->batch_code) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Bird Type <span class="text-danger">*</span></label>
                    <select name="bird_type" class="form-control" required>
                        @foreach(['Broiler','Layer','Sonali'] as $type)
                            <option value="{{ $type }}" {{ old('bird_type', $poultryBatch->bird_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Breed</label>
                    <input type="text" name="breed" class="form-control" value="{{ old('breed', $poultryBatch->breed) }}">
                </div>

                <div class="col-md-4 form-group">
                    <label>Opening Birds <span class="text-danger">*</span></label>
                    <input type="number" name="opening_birds" class="form-control" value="{{ old('opening_birds', $poultryBatch->opening_birds) }}" min="1" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Placement Date <span class="text-danger">*</span></label>
                    <input type="date" name="placement_date" class="form-control" value="{{ old('placement_date', $poultryBatch->placement_date) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Source Supplier</label>
                    <input type="text" name="source_supplier" class="form-control" value="{{ old('source_supplier', $poultryBatch->source_supplier) }}">
                </div>

                <div class="col-md-6 form-group">
                    <label>Cost Centre (Optional)</label>
                    <select name="cost_centre_id" class="form-control">
                        <option value="">-- None --</option>
                        @foreach($costCentres as $cc)
                            <option value="{{ $cc->id }}" {{ old('cost_centre_id', $poultryBatch->cost_centre_id) == $cc->id ? 'selected' : '' }}>
                                {{ $cc->centre_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $poultryBatch->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ old('status', $poultryBatch->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $poultryBatch->notes) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Batch</button>
            <a href="{{ route('poultry-batches.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection