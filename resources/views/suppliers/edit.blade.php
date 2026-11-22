@extends('layouts.master')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')
@section('breadcrumb', 'Procurement / Suppliers / Edit')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit: {{ $supplier->supplier_name }}</h3></div>
    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Supplier Code <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_code" class="form-control" value="{{ old('supplier_code', $supplier->supplier_code) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name', $supplier->supplier_name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Payment Terms</label>
                    <select name="payment_terms" class="form-control">
                        <option value="">-- Select --</option>
                        @foreach(['Cash','Credit 15 days','Credit 30 days','Credit 60 days'] as $term)
                            <option value="{{ $term }}" {{ old('payment_terms', $supplier->payment_terms) == $term ? 'selected' : '' }}>{{ $term }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Supplier</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection