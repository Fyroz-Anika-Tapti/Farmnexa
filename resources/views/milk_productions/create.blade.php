@extends('layouts.master')

@section('title', 'Add Milk Production')
@section('page-title', 'Add Milk Production')
@section('breadcrumb', 'Dairy / Milk Production / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Milk Production Details</h3>
    </div>
    <form action="{{ route('milk-productions.store') }}" method="POST">
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
                    <label>Section <span class="text-danger">*</span></label>
                    <select name="section_id" class="form-control" required>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Animal (Optional)</label>
                    <select name="animal_id" class="form-control">
                        <option value="">-- Group / All Animals --</option>
                        @foreach($animals as $animal)
                            <option value="{{ $animal->id }}" {{ old('animal_id') == $animal->id ? 'selected' : '' }}>
                                {{ $animal->animal_code }} ({{ ucfirst($animal->animal_type) }})
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
                        <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>Morning (সকাল)</option>
                        <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>Evening (বিকাল)</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Produced (Liters) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="produced_liters" class="form-control" value="{{ old('produced_liters') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Rejected (Liters)</label>
                    <input type="number" step="0.01" name="rejected_liters" class="form-control" value="{{ old('rejected_liters', '0.00') }}">
                    @error('rejected_liters') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Record</button>
            <a href="{{ route('milk-productions.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection