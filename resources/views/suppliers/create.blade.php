@extends('layouts.master')

@section('title', 'Add Supplier')
@section('page-title', 'Add Supplier')
@section('breadcrumb', 'Procurement / Suppliers / Create')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Supplier Details</h3></div>
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Supplier Code <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_code" class="form-control" value="{{ old('supplier_code') }}" placeholder="e.g. SUP-001" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Payment Terms</label>
                    <select name="payment_terms" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="Cash"          {{ old('payment_terms') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Credit 15 days" {{ old('payment_terms') == 'Credit 15 days' ? 'selected' : '' }}>Credit 15 days</option>
                        <option value="Credit 30 days" {{ old('payment_terms') == 'Credit 30 days' ? 'selected' : '' }}>Credit 30 days</option>
                        <option value="Credit 60 days" {{ old('payment_terms') == 'Credit 60 days' ? 'selected' : '' }}>Credit 60 days</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Supplier</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection