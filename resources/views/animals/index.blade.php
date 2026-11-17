@extends('layouts.master')

@section('title', 'Animals')
@section('page-title', 'Livestock Management')
@section('breadcrumb', 'Animals')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Animals</h3>
        <div class="card-tools">
            <a href="{{ route('animals.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Animal
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($animals->isEmpty())
            <div class="alert alert-info">No animals found. Add your first animal!</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Breed</th>
                        <th>Sex</th>
                        <th>Farm</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($animals as $animal)
                    <tr>
                        <td><strong>{{ $animal->animal_code }}</strong></td>
                        <td><span class="badge badge-info">{{ ucfirst($animal->animal_type) }}</span></td>
                        <td>{{ $animal->breed ?? '-' }}</td>
                        <td>{{ ucfirst($animal->sex ?? '-') }}</td>
                        <td>{{ $animal->farm->farm_name ?? 'N/A' }}</td>
                        <td>{{ $animal->section->section_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $animal->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($animal->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('animals.weights.index', $animal) }}" class="btn btn-success btn-sm" title="Weight History">
                                <i class="fas fa-weight"></i>
                            </a>
                            <a href="{{ route('animals.health.index', $animal) }}" class="btn btn-warning btn-sm" title="Health History">
                                <i class="fas fa-heartbeat"></i>
                            </a>
                            <a href="{{ route('animals.edit', $animal) }}" class="btn btn-info btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('animals.destroy', $animal) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $animals->links() }}</div>
        @endif
    </div>
</div>
@endsection