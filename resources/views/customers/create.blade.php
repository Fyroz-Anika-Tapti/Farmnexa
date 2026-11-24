@extends('layouts.master')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')
@section('breadcrumb', 'Sales / Customers / Create')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Customer Details</h3></div>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Customer Code <span class="text-danger">*</span></label>
                    <input type="text" name="customer_code" class="form-control" value="{{ old('customer_code', 'CUS-' . date('YmdHis')) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
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
                    <label>Customer Type <span class="text-danger">*</span></label>
                    <select name="customer_type" class="form-control" required>
                        <option value="retail"     {{ old('customer_type') == 'retail' ? 'selected' : '' }}>Retail</option>
                        <option value="wholesale"  {{ old('customer_type') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                        <option value="corporate"  {{ old('customer_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Opening Balance (BDT)</label>
                    <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', '0.00') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
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
            <button type="submit" class="btn btn-primary">Save Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection