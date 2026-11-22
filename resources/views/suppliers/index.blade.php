@extends('layouts.master')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers')
@section('breadcrumb', 'Procurement / Suppliers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Suppliers</h3>
        <div class="card-tools">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Supplier
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($suppliers->isEmpty())
            <div class="alert alert-info">No suppliers yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Payment Terms</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $s)
                    <tr>
                        <td><strong>{{ $s->supplier_code }}</strong></td>
                        <td>{{ $s->supplier_name }}</td>
                        <td>{{ $s->contact_person ?? '-' }}</td>
                        <td>{{ $s->phone ?? '-' }}</td>
                        <td>{{ $s->payment_terms ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $s->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $s) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this supplier?')">
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
            <div class="mt-3">{{ $suppliers->links() }}</div>
        @endif
    </div>
</div>
@endsection