@extends('layouts.master')

@section('title', 'Animal Details')
@section('page-title', 'Animal Details')
@section('breadcrumb', 'Animals / View')

@section('content')
{{-- Basic Info --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-cow"></i> {{ $animal->animal_code }} — {{ ucfirst($animal->animal_type) }}
        </h3>
        <div class="card-tools">
            <a href="{{ route('animals.edit', $animal) }}" class="btn btn-info btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('animals.index') }}" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Farm:</strong> {{ $animal->farm->farm_name ?? '-' }}</p>
                <p><strong>Section:</strong> {{ $animal->section->section_name ?? '-' }}</p>
                <p><strong>Breed:</strong> {{ $animal->breed ?? '-' }}</p>
                <p><strong>Sex:</strong> {{ ucfirst($animal->sex ?? '-') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Date of Birth:</strong> {{ $animal->date_of_birth ?? '-' }}</p>
                <p><strong>Purchase Date:</strong> {{ $animal->purchase_date ?? '-' }}</p>
                <p><strong>Status:</strong>
                    <span class="badge badge-{{ $animal->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($animal->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Cost Summary --}}
<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calculator"></i> Cost Summary & Profit</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <td>Purchase Value</td>
                        <td class="text-right">{{ number_format($animal->purchase_value ?? 0, 2) }} BDT</td>
                    </tr>
                    <tr>
                        <td>Medicine Cost</td>
                        <td class="text-right">{{ number_format($animal->total_medicine_cost, 2) }} BDT</td>
                    </tr>
                    <tr>
                        <td>Feed Cost</td>
                        <td class="text-right">{{ number_format($animal->total_feed_cost, 2) }} BDT</td>
                    </tr>
                    <tr>
                        <td>Other Expenses</td>
                        <td class="text-right">{{ number_format($animal->total_other_expense, 2) }} BDT</td>
                    </tr>
                    <tr class="bg-warning">
                        <td><strong>TOTAL COST</strong></td>
                        <td class="text-right"><strong>{{ number_format($animal->total_cost, 2) }} BDT</strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                @if($animal->sale)
                <div class="alert alert-success">
                    <h5><i class="fas fa-tag"></i> Sold</h5>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Selling Price:</td>
                            <td class="text-right">{{ number_format($animal->sale->selling_price, 2) }} BDT</td>
                        </tr>
                        <tr>
                            <td>Received:</td>
                            <td class="text-right">{{ number_format($animal->sale->proceeds_received, 2) }} BDT</td>
                        </tr>
                        <tr class="{{ $animal->profit >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                            <td><strong>PROFIT/LOSS</strong></td>
                            <td class="text-right"><strong>{{ number_format($animal->profit, 2) }} BDT</strong></td>
                        </tr>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Animal is still active. Profit will be calculated after sale.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Quick Action Buttons --}}
<div class="card">
    <div class="card-body">
        <a href="{{ route('animals.feeds.index', $animal) }}" class="btn btn-info btn-sm">
            <i class="fas fa-utensils"></i> Feed Records ({{ $animal->feedRecords->count() }})
        </a>
        <a href="{{ route('animals.expenses.index', $animal) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-money-bill"></i> Expenses ({{ $animal->expenses->count() }})
        </a>
        <a href="{{ route('animals.weights.index', $animal) }}" class="btn btn-success btn-sm">
            <i class="fas fa-weight"></i> Weights ({{ $animal->weights->count() }})
        </a>
        <a href="{{ route('animals.health.index', $animal) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-heartbeat"></i> Health ({{ $animal->healthEvents->count() }})
        </a>
    </div>
</div>
@endsection