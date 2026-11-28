@extends('layouts.master')

@section('title', 'Milk Processing')
@section('page-title', 'Milk Processing')
@section('breadcrumb', 'Milk / Processing')

@push('styles')
<style>
    .stat-card { background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-left: 5px solid #007bff; transition: all 0.3s; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card.orange { border-left-color: #fd7e14; }
    .stat-card.green { border-left-color: #28a745; }
    .stat-card.purple { border-left-color: #6f42c1; }
    .stat-value { font-size: 26px; font-weight: 700; margin: 0; }
    .stat-label { font-size: 12px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }
    .filter-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
    .batch-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .batch-table thead { background: #343a40; color: white; }
    .batch-table thead th { padding: 12px; font-size: 12px; text-transform: uppercase; border: none; }
    .batch-table tbody td { padding: 12px; vertical-align: middle; border-color: #f0f0f0; }
    .batch-table tbody tr:hover { background: #f8f9fa; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0"><i class="fas fa-blender text-warning"></i> Milk Processing</h2>
        <small class="text-muted">Convert milk into value-added products</small>
    </div>
    <a href="{{ route('milk-processings.create') }}" class="btn btn-warning btn-lg" style="border-radius: 10px;">
        <i class="fas fa-plus"></i> New Batch
    </a>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <p class="stat-value text-primary">{{ number_format($totals['input_milk'], 1) }} L</p>
            <p class="stat-label">Total Milk Input</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <p class="stat-value text-warning">{{ number_format($totals['output'], 1) }}</p>
            <p class="stat-label">Total Output</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green">
            <p class="stat-value text-success">{{ number_format($totals['revenue'], 2) }} BDT</p>
            <p class="stat-label">Total Revenue</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card purple">
            <p class="stat-value" style="color:#6f42c1;">{{ number_format($totals['revenue'] - $totals['cost'], 2) }} BDT</p>
            <p class="stat-label">Total Profit (Est.)</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-card">
    <form method="GET" action="{{ route('milk-processings.index') }}">
        <div class="row">
            <div class="col-md-2">
                <label class="small font-weight-bold">FARM</label>
                <select name="farm_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($farms as $farm)
                        <option value="{{ $farm->id }}" {{ request('farm_id') == $farm->id ? 'selected' : '' }}>{{ $farm->farm_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">SECTION</label>
                <select name="section_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">PRODUCT</label>
                <input type="text" name="product_name" class="form-control form-control-sm" value="{{ request('product_name') }}" placeholder="e.g. Curd">
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">FROM</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">TO</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-warning btn-sm mr-1" style="flex:1;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('milk-processings.index') }}" class="btn btn-secondary btn-sm" style="flex:1;">Clear</a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="batch-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th class="text-right">Input</th>
                <th class="text-right">Output</th>
                <th class="text-right">Revenue</th>
                <th class="text-right">Profit</th>
                <th width="80px" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($processings as $proc)
            <tr>
                <td><strong>{{ $proc->processing_date->format('d M, Y') }}</strong></td>
                <td>
                    <span class="badge badge-warning" style="font-size: 13px; padding: 6px 12px;">
                        {{ $proc->product_name }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($proc->input_milk_liters, 2) }} L</td>
                <td class="text-right">{{ number_format($proc->output_quantity, 2) }} {{ $proc->unit }}</td>
                <td class="text-right">{{ number_format($proc->total_value, 2) }}</td>
                <td class="text-right {{ $proc->profit >= 0 ? 'text-success' : 'text-danger' }}">
                    <strong>{{ number_format($proc->profit, 2) }} BDT</strong>
                </td>
                <td class="text-center">
                    <form action="{{ route('milk-processings.destroy', $proc) }}" method="POST" onsubmit="return confirm('Delete?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-blender" style="font-size:60px;color:#ddd;"></i>
                    <h5 class="mt-3">No processing batches yet</h5>
                    <a href="{{ route('milk-processings.create') }}" class="btn btn-warning">
                        <i class="fas fa-plus"></i> Create First Batch
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $processings->withQueryString()->links() }}</div>

@endsection