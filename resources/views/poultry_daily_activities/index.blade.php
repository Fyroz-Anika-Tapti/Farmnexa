@extends('layouts.master')

@section('title', 'Daily Activities')
@section('page-title', 'Daily Activities')
@section('breadcrumb', 'Poultry / Daily Activities')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Daily Activity Log for: <strong>{{ $batch->batch_code }}</strong> ({{ $batch->bird_type }})
        </h3>
        <div class="card-tools">
            <a href="{{ route('poultry-batches.daily.create', $batch) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Daily Record
            </a>
            <a href="{{ route('poultry-batches.index') }}" class="btn btn-default btn-sm ml-1">
                <i class="fas fa-arrow-left"></i> Back to Batches
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($activities->isEmpty())
            <div class="alert alert-info">No daily activities recorded yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Mortality</th>
                            <th>Culling</th>
                            <th>Trans In</th>
                            <th>Trans Out</th>
                            <th><strong>Closing Birds</strong></th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($activity->activity_date)->format('d M, Y') }}</td>
                            <td class="text-danger">{{ $activity->mortality }}</td>
                            <td class="text-danger">{{ $activity->culling }}</td>
                            <td class="text-success">{{ $activity->transfer_in }}</td>
                            <td class="text-warning">{{ $activity->transfer_out }}</td>
                            <td><strong>{{ number_format($activity->closing_birds) }}</strong></td>
                            <td>{{ $activity->notes ?? '-' }}</td>
                            <td>
                                <form action="{{ route('poultry-batches.daily.destroy', $activity) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?')">
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
            </div>
            <div class="mt-3">{{ $activities->links() }}</div>
        @endif
    </div>
</div>
@endsection