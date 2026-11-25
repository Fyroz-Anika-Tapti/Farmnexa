@extends('layouts.master')

@section('title', 'Expenses')
@section('page-title', 'Expenses')
@section('breadcrumb', 'Animals / Expenses')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-money-bill"></i> Expense History: {{ $animal->animal_code }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('animals.expenses.create', $animal) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Expense
            </a>
            <a href="{{ route('animals.show', $animal) }}" class="btn btn-default btn-sm ml-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($expenses->isEmpty())
            <div class="alert alert-info">No expenses yet.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($expenses as $exp)
                        @php $total += $exp->amount; @endphp
                        <tr>
                            <td>{{ $exp->expense_date->format('d M, Y') }}</td>
                            <td><span class="badge badge-info">{{ $exp->expense_type }}</span></td>
                            <td>{{ $exp->description ?? '-' }}</td>
                            <td class="text-right"><strong>{{ number_format($exp->amount, 2) }} BDT</strong></td>
                            <td>
                                <form action="{{ route('animals.expenses.destroy', $exp) }}" method="POST" onsubmit="return confirm('Delete this expense?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light">
                        <td colspan="3"><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($total, 2) }} BDT</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="mt-3">{{ $expenses->links() }}</div>
        @endif
    </div>
</div>
@endsection