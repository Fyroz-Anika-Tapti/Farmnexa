@extends('layouts.master')

@section('title', 'Egg Production')
@section('page-title', 'Egg Production')
@section('breadcrumb', 'Poultry / Egg Production')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Egg Production Records</h3>
        <div class="card-tools">
            <a href="{{ route('egg-productions.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Record
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($productions->isEmpty())
            <div class="alert alert-info">No egg production records found.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Farm</th>
                        <th>Batch</th>
                        <th>Produced</th>
                        <th>Rejected</th>
                        <th><strong>Saleable</strong></th>
                        <th>Weight (kg)</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productions as $prod)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($prod->production_date)->format('d M, Y') }}</td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($prod->shift) }}</span>
                        </td>
                        <td>{{ $prod->farm->farm_name ?? 'N/A' }}</td>
                        <td>{{ $prod->batch->batch_code ?? '-' }}</td>
                        <td>{{ number_format($prod->produced_eggs) }}</td>
                        <td class="text-danger">{{ number_format($prod->rejected_eggs) }}</td>
                        <td><strong>{{ number_format($prod->saleable_eggs) }}</strong></td>
                        <td>{{ $prod->total_weight_kg ? number_format($prod->total_weight_kg, 2) : '-' }}</td>
                        <td>
                            <form action="{{ route('egg-productions.destroy', $prod) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?')">
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