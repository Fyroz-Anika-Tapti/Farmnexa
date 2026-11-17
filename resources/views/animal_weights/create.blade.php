@extends('layouts.master')

@section('title', 'Add Weight')
@section('page-title', 'Add Weight Record')
@section('breadcrumb', 'Animals / Weight History / Add')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add Weight for: {{ $animal->animal_code }}</h3>
    </div>
    <form action="{{ route('animals.weights.store', $animal) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Record Date <span class="text-danger">*</span></label>
                    <input type="date" name="record_date" class="form-control @error('record_date') is-invalid @enderror" value="{{ old('record_date', date('Y-m-d')) }}" required>
                    @error('record_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="weight_kg" class="form-control @error('weight_kg') is-invalid @enderror" value="{{ old('weight_kg') }}" required>
                    @error('weight_kg') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="e.g. Monthly checkup">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Weight</button>
            <a href="{{ route('animals.weights.index', $animal) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection