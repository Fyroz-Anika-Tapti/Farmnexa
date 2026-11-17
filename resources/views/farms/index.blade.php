@extends('layouts.master')

@section('title', 'Farms')
@section('page-title', 'Farm List')
@section('breadcrumb', 'Farms')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Farms</h3>
        <div class="card-tools">
            <a href="{{ route('farms.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Farm
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($farms->isEmpty())
            <div class="alert alert-info">No farms found. Create your first farm!</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farms as $farm)
                    <tr>
                        <td><strong>{{ $farm->farm_code }}</strong></td>
                        <td>{{ $farm->farm_name }}</td>
                        <td>{{ $farm->location ?? '-' }}</td>
                        <td>{{ $farm->phone ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $farm->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($farm->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('farms.edit', $farm) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('farms.destroy', $farm) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
            <div class="mt-3">{{ $farms->links() }}</div>
        @endif
    </div>
</div>
@endsection