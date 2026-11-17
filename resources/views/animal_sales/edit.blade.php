@extends('layouts.master')

@section('title', 'Edit Animal Sale')
@section('page-title', 'Edit Animal Sale')
@section('breadcrumb', 'Livestock / Sales / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Sale for: {{ $animalSale->animal->animal_code ?? 'N/A' }}</h3>
    </div>
    <form action="{{ route('animal-sales.update', $animalSale) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Animal (Cannot be changed)</label>
                    <input type="text" class="form-control" value="{{ $animalSale->animal->animal_code ?? 'N/A' }} ({{ ucfirst($animalSale->animal->animal_type ?? '') }})" readonly disabled>
                    <small class="text-muted">Animal পরিবর্তন করা যাবে না — accounting policy</small>
                </div>
                <div class="col-md-6 form-group">
                    <label>Sale Date <span class="text-danger">*</span></label>
                    <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', $animalSale->sale_date) }}" required>
                    @error('sale_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $animalSale->customer_name) }}" required>
                    @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Customer Phone</label>
                    <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $animalSale->customer_phone) }}">
                </div>

                <div class="col-md-4 form-group">
                    <label>Carrying Value (BDT)</label>
                    <input type="number" step="0.01" name="carrying_value" class="form-control" value="{{ old('carrying_value', $animalSale->carrying_value) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Selling Price (BDT) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $animalSale->selling_price) }}" required>
                    @error('selling_price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Proceeds Received (BDT)</label>
                    <input type="number" step="0.01" name="proceeds_received" class="form-control" value="{{ old('proceeds_received', $animalSale->proceeds_received) }}">
                </div>

                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $animalSale->notes) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Sale</button>
            <a href="{{ route('animal-sales.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection