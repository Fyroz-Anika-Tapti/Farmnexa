@extends('layouts.master')

@section('title', 'Weight History')
@section('page-title', 'Weight History')
@section('breadcrumb', 'Animals / Weight History')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Weight History for: <strong>{{ $animal->animal_code }}</strong> ({{ $animal->breed }})
        </h3>
        <div class="card-tools">
            <a href="{{ route('animals.weights.create', $animal) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Weight
            </a>
            <a href="{{ route('animals.index') }}" class="btn btn-default btn-sm ml-1">
                <i class="fas fa-arrow-left"></i> Back to Animals
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($weights->isEmpty())
            <div class="alert alert-info">No weight records found for this animal.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Weight (kg)</th>
                        <th>Notes</th>
                        <th>Recorded By</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weights as $weight)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($weight->record_date)->format('d M, Y') }}</td>
                        <td><strong>{{ $weight->weight_kg }} kg</strong></td>
                        <td>{{ $weight->notes ?? '-' }}</td>
                        <td>{{ $weight->recorded_by ?? 'System' }}</td>
                        <td>
                            <form action="{{ route('animals.weights.destroy', $weight) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?')">
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
            <div class="mt-3">{{ $weights->links() }}</div>
        @endif
    </div>
</div>
@endsection