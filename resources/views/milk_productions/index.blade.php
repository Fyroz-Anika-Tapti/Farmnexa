@extends('layouts.master')

@section('title', 'Milk Production')
@section('page-title', 'Milk Production')
@section('breadcrumb', 'Dairy / Milk Production')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Milk Production Records</h3>
        <div class="card-tools">
            <a href="{{ route('milk-productions.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Record
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($productions->isEmpty())
            <div class="alert alert-info">No milk production records found.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Farm</th>
                        <th>Animal</th>
                        <th>Produced (L)</th>
                        <th>Rejected (L)</th>
                        <th><strong>Saleable (L)</strong></th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productions as $prod)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($prod->production_date)->format('d M, Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $prod->shift === 'morning' ? 'warning' : 'info' }}">
                                {{ ucfirst($prod->shift) }}
                            </span>
                        </td>
                        <td>{{ $prod->farm->farm_name ?? 'N/A' }}</td>
                        <td>{{ $prod->animal->animal_code ?? 'Group' }}</td>
                        <td>{{ number_format($prod->produced_liters, 2) }}</td>
                        <td class="text-danger">{{ number_format($prod->rejected_liters, 2) }}</td>
                        <td><strong>{{ number_format($prod->saleable_liters, 2) }}</strong></td>
                        <td>
                            <form action="{{ route('milk-productions.destroy', $prod) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?')">
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
            <div class="mt-3">{{ $productions->links() }}</div>
        @endif
    </div>
</div>
@endsection