@extends('layouts.master')

@section('title', 'Poultry Batches')
@section('page-title', 'Poultry Batches')
@section('breadcrumb', 'Poultry / Batches')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Poultry Batches</h3>
        <div class="card-tools">
            <a href="{{ route('poultry-batches.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Batch
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($batches->isEmpty())
            <div class="alert alert-info">No poultry batches found.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Batch Code</th>
                        <th>Type</th>
                        <th>Breed</th>
                        <th>Opening Birds</th>
                        <th>Placement Date</th>
                        <th>Farm</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batches as $batch)
                    <tr>
                        <td><strong>{{ $batch->batch_code }}</strong></td>
                        <td><span class="badge badge-info">{{ $batch->bird_type }}</span></td>
                        <td>{{ $batch->breed ?? '-' }}</td>
                        <td>{{ number_format($batch->opening_birds) }}</td>
                        <td>{{ \Carbon\Carbon::parse($batch->placement_date)->format('d M, Y') }}</td>
                        <td>{{ $batch->farm->farm_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $batch->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </td>
                        <td>
                            
                            <a href="{{ route('poultry-batches.daily.index', $batch) }}" class="btn btn-success btn-sm" title="Daily Activities">
                            <i class="fas fa-calendar-day"></i>
                            </a>
                        
                            <a href="{{ route('poultry-batches.edit', $batch) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('poultry-batches.destroy', $batch) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
            <div class="mt-3">{{ $batches->links() }}</div>
        @endif
    </div>
</div>
@endsection