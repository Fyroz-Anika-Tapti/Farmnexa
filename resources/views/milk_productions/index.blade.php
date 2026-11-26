@extends('layouts.master')

@section('title', 'Milk Production')
@section('page-title', 'Milk Production')
@section('breadcrumb', 'Milk / Production')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 5px solid #007bff;
        transition: all 0.3s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
    .stat-card.green { border-left-color: #28a745; }
    .stat-card.orange { border-left-color: #fd7e14; }
    .stat-card.purple { border-left-color: #6f42c1; }
    .stat-value { font-size: 28px; font-weight: 700; color: #333; margin: 0; }
    .stat-label { font-size: 12px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .milk-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .milk-table thead { background: #343a40; color: white; }
    .milk-table thead th { padding: 12px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border: none; }
    .milk-table tbody td { padding: 12px; vertical-align: middle; border-color: #f0f0f0; }
    .milk-table tbody tr:hover { background: #f8f9fa; }

    .badge-morning { background: #fd7e14; }
    .badge-evening { background: #6f42c1; }
</style>
@endpush

@section('content')

{{-- Header with Add Button --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0"><i class="fas fa-glass-whiskey text-primary"></i> Milk Production</h2>
        <small class="text-muted">Record and track daily milk production</small>
    </div>
    <a href="{{ route('milk-productions.create') }}" class="btn btn-primary btn-lg" style="border-radius: 10px;">
        <i class="fas fa-plus"></i> New Entry
    </a>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <p class="stat-value text-primary">{{ number_format($totals['produced'], 2) }} L</p>
            <p class="stat-label">Total Produced</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <p class="stat-value text-warning">{{ number_format($totals['rejected'], 2) }} L</p>
            <p class="stat-label">Total Rejected</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green">
            <p class="stat-value text-success">{{ number_format($totals['saleable'], 2) }} L</p>
            <p class="stat-label">Total Saleable</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card purple">
            <p class="stat-value text-purple" style="color:#6f42c1;">{{ $productions->total() }}</p>
            <p class="stat-label">Total Entries</p>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="filter-card">
    <form method="GET" action="{{ route('milk-productions.index') }}">
        <div class="row">
            <div class="col-md-2">
                <label class="small font-weight-bold">FARM</label>
                <select name="farm_id" class="form-control form-control-sm">
                    <option value="">All Farms</option>
                    @foreach($farms as $farm)
                        <option value="{{ $farm->id }}" {{ request('farm_id') == $farm->id ? 'selected' : '' }}>{{ $farm->farm_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">SECTION</label>
                <select name="section_id" class="form-control form-control-sm">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">FROM</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">TO</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">SHIFT</label>
                <select name="shift" class="form-control form-control-sm">
                    <option value="">All Shifts</option>
                    <option value="morning" {{ request('shift') == 'morning' ? 'selected' : '' }}>🌅 Morning</option>
                    <option value="evening" {{ request('shift') == 'evening' ? 'selected' : '' }}>🌙 Evening</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm mr-1" style="flex:1;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('milk-productions.index') }}" class="btn btn-secondary btn-sm" style="flex:1;">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="milk-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Shift</th>
                <th>Farm</th>
                <th>Animal</th>
                <th class="text-right">Produced (L)</th>
                <th class="text-right">Rejected (L)</th>
                <th class="text-right">Saleable (L)</th>
                <th width="80px" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productions as $prod)
            <tr>
                <td><strong>{{ \Carbon\Carbon::parse($prod->production_date)->format('d M, Y') }}</strong></td>
                <td>
                    <span class="badge badge-{{ $prod->shift === 'morning' ? 'morning' : 'evening' }}">
                        {{ $prod->shift === 'morning' ? '🌅 Morning' : '🌙 Evening' }}
                    </span>
                </td>
                <td>{{ $prod->farm->farm_name ?? '-' }}</td>
                <td>
                    @if($prod->animal)
                        <strong>{{ $prod->animal->animal_code }}</strong><br>
                        <small class="text-muted">{{ $prod->animal->breed ?? '-' }}</small>
                    @else
                        <span class="text-muted">Group</span>
                    @endif
                </td>
                <td class="text-right">{{ number_format($prod->produced_liters, 2) }}</td>
                <td class="text-right text-warning">{{ number_format($prod->rejected_liters, 2) }}</td>
                <td class="text-right text-success"><strong>{{ number_format($prod->saleable_liters, 2) }}</strong></td>
                <td class="text-center">
                    <form action="{{ route('milk-productions.destroy', $prod) }}" method="POST" onsubmit="return confirm('Delete this entry?')">
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
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-glass-whiskey" style="font-size:60px;color:#ddd;"></i>
                    <h5 class="mt-3">No milk production entries yet</h5>
                    <p class="text-muted">Start by adding a new entry</p>
                    <a href="{{ route('milk-productions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Entry
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $productions->withQueryString()->links() }}
</div>

@endsection