@extends('layouts.master')

@section('title', 'Cost Centres')
@section('page-title', 'Cost Centre List')
@section('breadcrumb', 'Cost Centres')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Cost Centres</h3>
        <div class="card-tools">
            <a href="{{ route('cost-centres.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Cost Centre
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($costCentres->isEmpty())
            <div class="alert alert-info">No cost centres found.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Farm</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($costCentres as $centre)
                    <tr>
                        <td><strong>{{ $centre->centre_code }}</strong></td>
                        <td>{{ $centre->centre_name }}</td>
                        <td>{{ $centre->farm->farm_name ?? 'N/A' }}</td>
                        <td>{{ $centre->section->section_name ?? 'Farm-wide' }}</td>
                        <td>
                            <span class="badge badge-{{ $centre->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($centre->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('cost-centres.edit', $centre) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('cost-centres.destroy', $centre) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
            <div class="mt-3">{{ $costCentres->links() }}</div>
        @endif
    </div>
</div>
@endsection