@extends('layouts.master')

@section('title', 'Add Egg Production')
@section('page-title', 'Add Egg Production')
@section('breadcrumb', 'Poultry / Egg Production / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Egg Production Details</h3>
    </div>
    <form action="{{ route('egg-productions.store') }}" method="POST">
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
                    <label>Section (Poultry) <span class="text-danger">*</span></label>
                    <select name="section_id" class="form-control" required>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }} ({{ $section->section_type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Batch (Optional)</label>
                    <select name="batch_id" class="form-control">
                        <option value="">-- Select Batch --</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->batch_code }} ({{ $batch->bird_type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Production Date <span class="text-danger">*</span></label>
                    <input type="date" name="production_date" class="form-control" value="{{ old('production_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4 form-group">
                    <label>Shift <span class="text-danger">*</span></label>
                    <select name="shift" class="form-control" required>
                        <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="afternoon" {{ old('shift') == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                        <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>Evening</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Produced Eggs <span class="text-danger">*</span></label>
                    <input type="number" name="produced_eggs" class="form-control" value="{{ old('produced_eggs') }}" min="0" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Rejected Eggs</label>
                    <input type="number" name="rejected_eggs" class="form-control" value="{{ old('rejected_eggs', 0) }}" min="0">
                    @error('rejected_eggs') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Total Weight (kg) — Optional</label>
                    <input type="number" step="0.01" name="total_weight_kg" class="form-control" value="{{ old('total_weight_kg') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Record</button>
            <a href="{{ route('egg-productions.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection