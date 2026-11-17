@extends('layouts.master')

@section('title', 'Health History')
@section('page-title', 'Health History')
@section('breadcrumb', 'Animals / Health History')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Health Events for: <strong>{{ $animal->animal_code }}</strong> ({{ $animal->breed }})
        </h3>
        <div class="card-tools">
            <a href="{{ route('animals.health.create', $animal) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Health Event
            </a>
            <a href="{{ route('animals.index') }}" class="btn btn-default btn-sm ml-1">
                <i class="fas fa-arrow-left"></i> Back to Animals
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($events->isEmpty())
            <div class="alert alert-info">No health events found for this animal.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Cost (BDT)</th>
                        <th>Vet</th>
                        <th>Follow-up</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</td>
                        <td>
                            <span class="badge badge-{{ 
                                $event->event_type === 'Vaccination' ? 'success' : 
                                ($event->event_type === 'Illness' ? 'danger' : 'info') 
                            }}">
                                {{ $event->event_type }}
                            </span>
                        </td>
                        <td>{{ $event->diagnosis ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($event->treatment_description, 40) ?? '-' }}</td>
                        <td>{{ number_format($event->medicine_cost, 2) }}</td>
                        <td>{{ $event->veterinarian_name ?? '-' }}</td>
                        <td>
                            {{ $event->next_follow_up_date ? \Carbon\Carbon::parse($event->next_follow_up_date)->format('d M, Y') : '-' }}
                        </td>
                        <td>
                            <form action="{{ route('animals.health.destroy', $event) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this event?')">
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
            <div class="mt-3">{{ $events->links() }}</div>
        @endif
    </div>
</div>
@endsection