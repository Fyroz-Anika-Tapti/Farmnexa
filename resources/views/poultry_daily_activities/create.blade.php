@extends('layouts.master')

@section('title', 'Add Daily Record')
@section('page-title', 'Add Daily Record')
@section('breadcrumb', 'Poultry / Daily Activities / Add')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daily Record for: {{ $batch->batch_code }}</h3>
    </div>
    <form action="{{ route('poultry-batches.daily.store', $batch) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Opening Birds:</strong> {{ number_format($openingBirds) }} birds available before this record.
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Activity Date <span class="text-danger">*</span></label>
                    <input type="date" name="activity_date" class="form-control" value="{{ old('activity_date', date('Y-m-d')) }}" required>
                </div>
                
                <div class="col-md-3 form-group">
                    <label>Mortality (মৃত) <span class="text-danger">*</span></label>
                    <input type="number" name="mortality" class="form-control" value="{{ old('mortality', 0) }}" min="0" required>
                </div>
                <div class="col-md-3 form-group">
                    <label>Culling (কাটা) <span class="text-danger">*</span></label>
                    <input type="number" name="culling" class="form-control" value="{{ old('culling', 0) }}" min="0" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Transfer In (আনা) <span class="text-danger">*</span></label>
                    <input type="number" name="transfer_in" class="form-control" value="{{ old('transfer_in', 0) }}" min="0" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Transfer Out (পাঠানো) <span class="text-danger">*</span></label>
                    <input type="number" name="transfer_out" class="form-control" value="{{ old('transfer_out', 0) }}" min="0" required>
                </div>

                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Daily Record</button>
            <a href="{{ route('poultry-batches.daily.index', $batch) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection