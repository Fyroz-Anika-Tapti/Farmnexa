@extends('layouts.master')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')
@section('breadcrumb', 'Sales / Customers / Edit')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit: {{ $customer->customer_name }}</h3></div>
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Customer Code <span class="text-danger">*</span></label>
                    <input type="text" name="customer_code" class="form-control" value="{{ old('customer_code', $customer->customer_code) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Customer Type <span class="text-danger">*</span></label>
                    <select name="customer_type" class="form-control" required>
                        @foreach(['retail','wholesale','corporate'] as $type)
                            <option value="{{ $type }}" {{ old('customer_type', $customer->customer_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Current Balance (auto)</label>
                    <input type="text" class="form-control" value="{{ number_format($customer->current_balance, 2) }} BDT" disabled>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active"   {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection