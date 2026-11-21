@extends('layouts.master')

@section('title', 'Milk Processing')
@section('page-title', 'Milk Processing')
@section('breadcrumb', 'Dairy / Milk Processing')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Milk Processing Records</h3>
        <div class="card-tools">
            <a href="{{ route('milk-processings.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Record
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($processings->isEmpty())
            <div class="alert alert-info">No milk processing records found.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Input Milk (L)</th>
                        <th>Output</th>
                        <th>Unit</th>
                        <th>Processing Cost</th>
                        <th>Value</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processings as $proc)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($proc->processing_date)->format('d M, Y') }}</td>
                        <td><strong>{{ $proc->product_name }}</strong></td>
                        <td>{{ number_format($proc->input_milk_liters, 2) }} L</td>
                        <td>{{ number_format($proc->output_quantity, 2) }}</td>
                        <td>{{ $proc->unit }}</td>
                        <td>{{ number_format($proc->processing_cost, 2) }} BDT</td>
                        <td>{{ number_format($proc->total_value, 2) }} BDT</td>
                        <td>
                            <form action="{{ route('milk-processings.destroy', $proc) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?')">
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
            <div class="mt-3">{{ $processings->links() }}</div>
        @endif
    </div>
</div>
@endsection