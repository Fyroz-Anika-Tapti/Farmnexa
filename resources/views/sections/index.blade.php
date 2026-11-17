@extends('layouts.master')

@section('title', 'Sections')
@section('page-title', 'Section List')
@section('breadcrumb', 'Sections')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Sections</h3>
        <div class="card-tools">
            <a href="{{ route('sections.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Section
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($sections->isEmpty())
            <div class="alert alert-info">No sections found. Create your first section!</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Farm</th>
                        <th>Section Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                    <tr>
                        <td>{{ $section->farm->farm_name ?? 'N/A' }}</td>
                        <td><strong>{{ $section->section_name }}</strong></td>
                        <td><span class="badge badge-info">{{ $section->section_type }}</span></td>
                        <td>
                            <span class="badge badge-{{ $section->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($section->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('sections.edit', $section) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('sections.destroy', $section) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
            <div class="mt-3">{{ $sections->links() }}</div>
        @endif
    </div>
</div>
@endsection