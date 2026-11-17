@extends('layouts.master')

@section('title', 'Add Animal')
@section('page-title', 'Add New Animal')
@section('breadcrumb', 'Animals / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Animal Details</h3>
    </div>
    <form action="{{ route('animals.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" id="farm_id" class="form-control" required>
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
                    <select name="section_id" id="section_id" class="form-control" required>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-farm="{{ $section->farm_id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Animal Code <span class="text-danger">*</span></label>
                    <input type="text" name="animal_code" class="form-control @error('animal_code') is-invalid @enderror" value="{{ old('animal_code') }}" placeholder="e.g. CAT-001" required>
                    @error('animal_code') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Animal Type <span class="text-danger">*</span></label>
                    <select name="animal_type" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        <option value="cattle"  {{ old('animal_type') == 'cattle' ? 'selected' : '' }}>Cattle</option>
                        <option value="goat"    {{ old('animal_type') == 'goat' ? 'selected' : '' }}>Goat</option>
                        <option value="sheep"   {{ old('animal_type') == 'sheep' ? 'selected' : '' }}>Sheep</option>
                        <option value="buffalo" {{ old('animal_type') == 'buffalo' ? 'selected' : '' }}>Buffalo</option>
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label>Breed</label>
                    <input type="text" name="breed" class="form-control" value="{{ old('breed') }}" placeholder="e.g. Holstein Friesian">
                </div>
                <div class="col-md-6 form-group">
                    <label>Sex</label>
                    <select name="sex" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="male"   {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Purchase Date</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Purchase Value (BDT)</label>
                    <input type="number" step="0.01" name="purchase_value" class="form-control" value="{{ old('purchase_value') }}">
                </div>

                <div class="col-md-6 form-group">
                    <label>Source Type</label>
                    <select name="source_type" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="purchased" {{ old('source_type') == 'purchased' ? 'selected' : '' }}>Purchased</option>
                        <option value="born"      {{ old('source_type') == 'born' ? 'selected' : '' }}>Born on Farm</option>
                        <option value="transferred" {{ old('source_type') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="sold"     {{ old('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="deceased" {{ old('status') === 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Animal</button>
            <a href="{{ route('animals.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Farm সিলেক্ট করলে শুধু সেই farm-এর section দেখাবে
    document.getElementById('farm_id').addEventListener('change', function () {
        const farmId = this.value;
        document.querySelectorAll('#section_id option').forEach(opt => {
            if (!opt.value) return;
            opt.style.display = (!farmId || opt.dataset.farm === farmId) ? '' : 'none';
        });
    });
</script>
@endpush