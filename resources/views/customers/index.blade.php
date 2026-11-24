@extends('layouts.master')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('breadcrumb', 'Sales / Customers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Customers</h3>
        <div class="card-tools">
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Customer
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($customers->isEmpty())
            <div class="alert alert-info">No customers yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Due Balance</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $c)
                    <tr>
                        <td><strong>{{ $c->customer_code }}</strong></td>
                        <td>{{ $c->customer_name }}</td>
                        <td>{{ $c->phone ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($c->customer_type) }}</span></td>
                        <td class="{{ $c->current_balance > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($c->current_balance, 2) }} BDT
                        </td>
                        <td>
                            <span class="badge badge-{{ $c->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customers.edit', $c) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $c) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this customer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection