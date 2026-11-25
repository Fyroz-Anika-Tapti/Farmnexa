@extends('layouts.master')

@section('title', 'Animals')
@section('page-title', 'Livestock Management')
@section('breadcrumb', 'Animals')

@section('content')
<div class="card">
        <div class="card-header">
            <h3 class="card-title">All Animals</h3>
            <div class="card-tools">
                <a href="{{ route('animals.feeds.bulk') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-utensils"></i> Bulk Feed
                </a>
                <a href="{{ route('animals.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Animal
                </a>
            </div>
        </div>
    <div class="card-body">
        @if($animals->isEmpty())
            <div class="alert alert-info">No animals found. Add your first animal!</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Breed</th>
                        <th>Sex</th>
                        <th>Farm</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($animals as $animal)
                    <tr>
                        <td><strong>{{ $animal->animal_code }}</strong></td>
                        <td><span class="badge badge-info">{{ ucfirst($animal->animal_type) }}</span></td>
                        <td>{{ $animal->breed ?? '-' }}</td>
                        <td>{{ ucfirst($animal->sex ?? '-') }}</td>
                        <td>{{ $animal->farm->farm_name ?? 'N/A' }}</td>
                        <td>{{ $animal->section->section_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $animal->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($animal->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        {{-- View Details --}}
                                        <a href="{{ route('animals.show', $animal) }}" class="dropdown-item">
                                            <i class="fas fa-eye text-info mr-2"></i> View Details & Cost
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        {{-- Feed Records --}}
                                        <a href="{{ route('animals.feeds.index', $animal) }}" class="dropdown-item">
                                            <i class="fas fa-utensils text-primary mr-2"></i> Feed Records
                                        </a>

                                        {{-- Other Expenses --}}
                                        <a href="{{ route('animals.expenses.index', $animal) }}" class="dropdown-item">
                                            <i class="fas fa-money-bill text-warning mr-2"></i> Other Expenses
                                        </a>

                                        {{-- Weight History --}}
                                        <a href="{{ route('animals.weights.index', $animal) }}" class="dropdown-item">
                                            <i class="fas fa-weight text-success mr-2"></i> Weight History
                                        </a>

                                        {{-- Health History --}}
                                        <a href="{{ route('animals.health.index', $animal) }}" class="dropdown-item">
                                            <i class="fas fa-heartbeat text-danger mr-2"></i> Health History
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        {{-- Edit --}}
                                        <a href="{{ route('animals.edit', $animal) }}" class="dropdown-item">
                                            <i class="fas fa-edit text-info mr-2"></i> Edit
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('animals.destroy', $animal) }}" method="POST" onsubmit="return confirm('Delete this animal?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash mr-2"></i> Delete
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $animals->links() }}</div>
        @endif
    </div>
</div>
@endsection