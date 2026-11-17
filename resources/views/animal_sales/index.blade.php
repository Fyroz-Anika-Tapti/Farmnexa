@extends('layouts.master')

@section('title', 'Animal Sales')
@section('page-title', 'Animal Sales')
@section('breadcrumb', 'Livestock / Sales')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Animal Sales</h3>
        <div class="card-tools">
            <a href="{{ route('animal-sales.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Record New Sale
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($sales->isEmpty())
            <div class="alert alert-info">No sales recorded yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Animal</th>
                        <th>Customer</th>
                        <th>Selling Price</th>
                        <th>Received</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</td>
                        <td>
                            <strong>{{ $sale->animal->animal_code ?? 'N/A' }}</strong>
                            @if($sale->animal)
                                ({{ ucfirst($sale->animal->animal_type) }})
                            @endif
                        </td>
                        <td>
                            {{ $sale->customer_name }}
                            @if($sale->customer_phone)
                                <br><small class="text-muted">{{ $sale->customer_phone }}</small>
                            @endif
                        </td>
                        <td>{{ number_format($sale->selling_price, 2) }} BDT</td>
                        <td>{{ number_format($sale->proceeds_received, 2) }} BDT</td>
                        <td class="text-danger">
                            {{ number_format($sale->selling_price - $sale->proceeds_received, 2) }} BDT
                        </td>
                        <td>
                        <a href="{{ route('animal-sales.edit', $sale) }}" class="btn btn-info btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('animal-sales.destroy', $sale) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this sale?')">
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
            <div class="mt-3">{{ $sales->links() }}</div>
        @endif
    </div>
</div>
@endsection