@extends('layouts.master')

@section('title', 'Record Animal Sale')
@section('page-title', 'Record Animal Sale')
@section('breadcrumb', 'Livestock / Sales / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sale Details</h3>
    </div>
    <form action="{{ route('animal-sales.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Animal <span class="text-danger">*</span></label>
                    <select name="animal_id" class="form-control @error('animal_id') is-invalid @enderror" required>
                        <option value="">-- Select Animal --</option>
                        @foreach($animals as $animal)
                            <option value="{{ $animal->id }}" {{ old('animal_id') == $animal->id ? 'selected' : '' }}>
                                {{ $animal->animal_code }} ({{ ucfirst($animal->animal_type) }}, {{ $animal->breed ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('animal_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Sale Date <span class="text-danger">*</span></label>
                    <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required>
                    @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Customer Phone</label>
                    <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                </div>

                <div class="col-md-4 form-group">
                    <label>Carrying Value (BDT)</label>
                    <input type="number" step="0.01" name="carrying_value" class="form-control" value="{{ old('carrying_value', '0.00') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Selling Price (BDT) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" required>
                    @error('selling_price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Proceeds Received (BDT)</label>
                    <input type="number" step="0.01" name="proceeds_received" class="form-control" value="{{ old('proceeds_received', '0.00') }}">
                </div>

                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Sale</button>
            <a href="{{ route('animal-sales.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection