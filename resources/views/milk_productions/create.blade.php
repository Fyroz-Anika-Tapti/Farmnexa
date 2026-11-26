@extends('layouts.master')

@section('title', 'Milk Production Entry')
@section('page-title', '')
@section('breadcrumb', 'Milk / Production / New')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/css/select2-bootstrap4.min.css">
<style>
    .hero-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .hero-card h2 { font-size: 32px; font-weight: 700; margin: 0; }
    .hero-card small { opacity: 0.9; font-size: 13px; }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid #007bff;
        transition: all 0.3s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
    .stat-card.green { border-left-color: #28a745; }
    .stat-card.orange { border-left-color: #fd7e14; }
    .stat-card.purple { border-left-color: #6f42c1; }

    .stat-value { font-size: 28px; font-weight: 700; color: #333; margin: 0; }
    .stat-label { font-size: 13px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; }

    .animal-card {
        background: white;
        border: 2px solid #e3e3e3;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .animal-card:hover { border-color: #007bff; box-shadow: 0 4px 12px rgba(0,123,255,0.15); }
    .animal-card.selected { border-color: #28a745; background: #f0fdf4; }
    .animal-card.already-entered { opacity: 0.6; background: #f8f9fa; }

    .animal-code {
        font-weight: 700;
        font-size: 16px;
        color: #333;
    }
    .animal-meta { font-size: 12px; color: #888; }
    .animal-breed { font-size: 13px; color: #555; }

    .qty-input {
        font-size: 16px;
        font-weight: 600;
        text-align: center;
    }
    .qty-input:focus { border-color: #28a745; box-shadow: 0 0 0 3px rgba(40,167,69,0.15); }

    .summary-bar {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
        z-index: 100;
        margin-top: 30px;
    }
    .summary-item {
        display: inline-block;
        margin-right: 25px;
        font-size: 14px;
    }
    .summary-item strong { font-size: 22px; color: #333; }

    .save-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 12px 35px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(40,167,69,0.4);
        transition: all 0.3s;
    }
    .save-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(40,167,69,0.5); color: white; }
    .save-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .shift-btn {
        padding: 10px 25px;
        border-radius: 25px;
        border: 2px solid #ddd;
        background: white;
        font-weight: 600;
        transition: all 0.2s;
    }
    .shift-btn.active { background: #007bff; color: white; border-color: #007bff; }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0f0f0;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #999;
    }
    .empty-state i { font-size: 60px; margin-bottom: 20px; }
</style>
@endpush

@section('content')

{{-- Success/Error Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-check-circle"></i> <strong>{{ session('success') }}</strong>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('milk-productions.store') }}" method="POST" id="milk_form">
    @csrf

    {{-- HERO HEADER --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="hero-card">
                <small>Today's Production</small>
                <h2>{{ number_format($todayMilk->total ?? 0, 1) }} L</h2>
                <small>{{ now()->format('d M, Y') }}</small>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card green">
                        <p class="stat-value">{{ number_format($yesterdayMilk->total ?? 0, 1) }} L</p>
                        <p class="stat-label">Yesterday</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card orange">
                        <p class="stat-value">{{ number_format($weekMilk, 1) }} L</p>
                        <p class="stat-label">This Week</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card purple">
                        <p class="stat-value">{{ $animals->count() }}</p>
                        <p class="stat-label">Active Cows</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ENTRY SETUP --}}
    <div class="card shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="small font-weight-bold">FARM</label>
                    <select name="farm_id" class="form-control select2" required>
                        <option value="">-- Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>{{ $farm->farm_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">SECTION</label>
                    <select name="section_id" class="form-control select2" required>
                        <option value="">-- Section --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">DATE</label>
                    <input type="date" name="production_date" class="form-control" value="{{ old('production_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">SHIFT</label>
                    <div>
                        <label class="shift-btn {{ $autoShift === 'morning' ? 'active' : '' }}" id="btn_morning" style="cursor:pointer;">
                            🌅 Morning
                        </label>
                        <label class="shift-btn {{ $autoShift === 'evening' ? 'active' : '' }}" id="btn_evening" style="cursor:pointer;">
                            🌙 Evening
                        </label>
                        <input type="hidden" name="shift" id="shift_input" value="{{ $autoShift }}" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PER ANIMAL ENTRY --}}
    <div class="card shadow-sm" style="border-radius: 12px; margin-top: 20px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="section-title mb-0">🐄 Per Animal Entry</div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-success" id="select_all_btn">
                        <i class="fas fa-check-double"></i> Select All
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect_all_btn">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>

            @if($animals->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-cow"></i>
                    <h4>No active dairy cows</h4>
                    <p>Add female cattle to record milk production.</p>
                </div>
            @else
                <div class="row">
                    @foreach($animals as $animal)
                        @php
                            $isEntered = in_array($animal->id, $alreadyEntered);
                            $lastMilk = $lastMilkMap[$animal->id] ?? null;
                            $age = $animal->date_of_birth ? \Carbon\Carbon::parse($animal->date_of_birth)->diffInYears(now()) : null;
                        @endphp
                        <div class="col-md-6">
                            <div class="animal-card {{ $isEntered ? 'already-entered' : '' }}"
                                 data-animal-id="{{ $animal->id }}"
                                 onclick="toggleCard(this, {{ $isEntered ? 'true' : 'false' }})">

                                {{-- Hidden input --}}
                                <input type="hidden" name="entries[{{ $animal->id }}][animal_id]" value="{{ $animal->id }}" disabled class="animal-hidden">
                                <input type="hidden" name="entries[{{ $animal->id }}][produced_liters]" class="produced-hidden" disabled>
                                <input type="hidden" name="entries[{{ $animal->id }}][rejected_liters]" class="rejected-hidden" disabled>

                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="mr-2 animal-checkbox" onclick="event.stopPropagation()" {{ $isEntered ? 'disabled' : '' }}>
                                            <div>
                                                <div class="animal-code">{{ $animal->animal_code }}</div>
                                                <div class="animal-breed">{{ $animal->breed ?? 'No Breed' }}</div>
                                                <div class="animal-meta mt-1">
                                                    @if($age) Age: {{ $age }}y @endif
                                                    @if($lastMilk) | Last: <strong>{{ number_format($lastMilk, 1) }} L</strong> @endif
                                                    @if($isEntered) | <span class="badge badge-info">Already Entered</span> @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(!$isEntered)
                                <div class="mt-3 qty-section" style="display:none;">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="small mb-1">Produced (L)</label>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm qty-input produced-input"
                                                   placeholder="0.00" onclick="event.stopPropagation()">
                                        </div>
                                        <div class="col-6">
                                            <label class="small mb-1">Rejected (L)</label>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm qty-input rejected-input"
                                                   placeholder="0.00" onclick="event.stopPropagation()">
                                        </div>
                                    </div>
                                    <div class="text-right mt-2">
                                        <small class="text-success">Saleable: <strong class="saleable-display">0.00 L</strong></small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-3">
                <label class="small font-weight-bold">NOTES</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    {{-- STICKY SUMMARY BAR --}}
    <div class="summary-bar">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="summary-item">
                    Selected: <strong id="selected_count">0</strong>
                </span>
                <span class="summary-item text-success">
                    Produced: <strong id="total_produced">0.00</strong> L
                </span>
                <span class="summary-item text-danger">
                    Rejected: <strong id="total_rejected">0.00</strong> L
                </span>
                <span class="summary-item text-primary">
                    Saleable: <strong id="total_saleable">0.00</strong> L
                </span>
            </div>
            <div>
                <a href="{{ route('milk-productions.index') }}" class="btn btn-light mr-2">Cancel</a>
                <button type="submit" class="save-btn" id="save_btn" disabled>
                    <i class="fas fa-save"></i> Save All Entries
                </button>
            </div>
        </div>
    </div>

</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4', placeholder: '-- Select --', allowClear: true });
    });

    // Shift toggle
    document.getElementById('btn_morning').addEventListener('click', function() {
        setShift('morning');
    });
    document.getElementById('btn_evening').addEventListener('click', function() {
        setShift('evening');
    });
    function setShift(shift) {
        document.getElementById('shift_input').value = shift;
        document.getElementById('btn_morning').classList.toggle('active', shift === 'morning');
        document.getElementById('btn_evening').classList.toggle('active', shift === 'evening');
    }

    // Toggle animal card
    function toggleCard(card, isEntered) {
        if (isEntered) return;

        const checkbox = card.querySelector('.animal-checkbox');
        const qtySection = card.querySelector('.qty-section');

        if (checkbox.checked) {
            checkbox.checked = false;
            card.classList.remove('selected');
            qtySection.style.display = 'none';
            disableCardInputs(card);
        } else {
            checkbox.checked = true;
            card.classList.add('selected');
            qtySection.style.display = 'block';
            enableCardInputs(card);
            card.querySelector('.produced-input').focus();
        }
        updateSummary();
    }

    function enableCardInputs(card) {
        card.querySelectorAll('input[disabled]:not(.animal-checkbox)').forEach(i => i.disabled = false);
    }

    function disableCardInputs(card) {
        card.querySelectorAll('input.animal-hidden, input.produced-hidden, input.rejected-hidden').forEach(i => i.disabled = true);
        card.querySelector('.produced-input').value = '';
        card.querySelector('.rejected-input').value = '';
        card.querySelector('.saleable-display').textContent = '0.00 L';
    }

    // Qty input change
    document.querySelectorAll('.produced-input, .rejected-input').forEach(input => {
        input.addEventListener('input', function(e) {
            e.stopPropagation();
            const card = this.closest('.animal-card');
            const produced = parseFloat(card.querySelector('.produced-input').value) || 0;
            const rejected = parseFloat(card.querySelector('.rejected-input').value) || 0;
            const saleable = Math.max(0, produced - rejected);

            card.querySelector('.saleable-display').textContent = saleable.toFixed(2) + ' L';

            // Update hidden inputs
            card.querySelector('.produced-hidden').value = produced;
            card.querySelector('.rejected-hidden').value = rejected;

            updateSummary();
        });
    });

    // Select all / Deselect
    document.getElementById('select_all_btn').addEventListener('click', function() {
        document.querySelectorAll('.animal-card:not(.already-entered)').forEach(card => {
            if (!card.classList.contains('selected')) toggleCard(card, false);
        });
    });
    document.getElementById('deselect_all_btn').addEventListener('click', function() {
        document.querySelectorAll('.animal-card.selected').forEach(card => {
            toggleCard(card, false);
        });
    });

    // Update summary
    function updateSummary() {
        let count = 0, produced = 0, rejected = 0;

        document.querySelectorAll('.animal-card.selected').forEach(card => {
            count++;
            produced += parseFloat(card.querySelector('.produced-input').value) || 0;
            rejected += parseFloat(card.querySelector('.rejected-input').value) || 0;
        });

        document.getElementById('selected_count').textContent = count;
        document.getElementById('total_produced').textContent = produced.toFixed(2);
        document.getElementById('total_rejected').textContent = rejected.toFixed(2);
        document.getElementById('total_saleable').textContent = (produced - rejected).toFixed(2);

        document.getElementById('save_btn').disabled = count === 0;
    }
</script>
@endpush