@extends('layouts.master')

@section('title', 'Add Expense')
@section('page-title', 'Add Expense')
@section('breadcrumb', 'Animals / Expenses / Add')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Expense for: {{ $animal->animal_code }}</h3>
    </div>
    <form action="{{ route('animals.expenses.store', $animal) }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Expense Type <span class="text-danger">*</span></label>
                    <select name="expense_type" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        @foreach(['Labor', 'Transport', 'Utility', 'Rent', 'Maintenance', 'Veterinary', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('expense_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Amount (BDT) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                </div>
                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Expense
            </button>
            <a href="{{ route('animals.expenses.index', $animal) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection