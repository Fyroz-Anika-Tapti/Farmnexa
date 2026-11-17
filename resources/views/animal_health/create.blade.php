@extends('layouts.master')

@section('title', 'Add Health Event')
@section('page-title', 'Add Health Event')
@section('breadcrumb', 'Animals / Health / Add')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add Health Event for: {{ $animal->animal_code }}</h3>
    </div>
    <form action="{{ route('animals.health.store', $animal) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Event Type <span class="text-danger">*</span></label>
                    <select name="event_type" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        @foreach(['Vaccination', 'Treatment', 'Check-up', 'Illness', 'Deworming'] as $type)
                            <option value="{{ $type }}" {{ old('event_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('event_type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Event Date <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" class="form-control" value="{{ old('event_date', date('Y-m-d')) }}" required>
                    @error('event_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Diagnosis</label>
                    <input type="text" name="diagnosis" class="form-control" value="{{ old('diagnosis') }}" placeholder="e.g. Foot and Mouth Disease">
                </div>
                <div class="col-md-6 form-group">
                    <label>Veterinarian Name</label>
                    <input type="text" name="veterinarian_name" class="form-control" value="{{ old('veterinarian_name') }}">
                </div>

                <div class="col-md-12 form-group">
                    <label>Treatment Description</label>
                    <textarea name="treatment_description" class="form-control" rows="3">{{ old('treatment_description') }}</textarea>
                </div>

                <div class="col-md-6 form-group">
                    <label>Medicine Cost (BDT)</label>
                    <input type="number" step="0.01" name="medicine_cost" class="form-control" value="{{ old('medicine_cost', '0.00') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Next Follow-up Date</label>
                    <input type="date" name="next_follow_up_date" class="form-control" value="{{ old('next_follow_up_date') }}">
                    @error('next_follow_up_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Health Event</button>
            <a href="{{ route('animals.health.index', $animal) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection