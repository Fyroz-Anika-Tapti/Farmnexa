@extends('layouts.master')

@section('title', 'Milk Processing')
@section('page-title', '')
@section('breadcrumb', 'Milk / Processing / New')

@push('styles')
<style>
    .hero-card {
        background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
        color: white;
        padding: 22px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(253, 126, 20, 0.4);
    }
    .hero-card h2 { font-size: 30px; font-weight: 700; margin: 0; }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid #007bff;
        transition: all 0.3s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card.green { border-left-color: #28a745; }
    .stat-card.purple { border-left-color: #6f42c1; }
    .stat-value { font-size: 26px; font-weight: 700; margin: 0; }
    .stat-label { font-size: 12px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; }

    .product-chip {
        display: inline-block;
        padding: 10px 20px;
        margin: 5px;
        border: 2px solid #e3e3e3;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
    }
    .product-chip:hover { border-color: #fd7e14; background: #fff5ec; }
    .product-chip.active { background: #fd7e14; color: white; border-color: #fd7e14; }

    .input-group-custom {
        background: white;
        border: 2px solid #e3e3e3;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        transition: border-color 0.2s;
    }
    .input-group-custom:focus-within { border-color: #fd7e14; }
    .input-group-custom label { font-size: 12px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block; }
    .input-group-custom .form-control { border: none; padding: 0; font-size: 20px; font-weight: 700; background: transparent; }
    .input-group-custom .form-control:focus { box-shadow: none; }
    .input-group-custom .unit-label { font-size: 14px; color: #999; font-weight: 600; align-self: flex-end; padding-bottom: 5px; }

    .profit-display {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        padding: 20px;
        border-radius: 12px;
        border-left: 5px solid #28a745;
    }
    .profit-display .label { font-size: 13px; color: #155724; text-transform: uppercase; font-weight: 600; }
    .profit-display .value { font-size: 32px; font-weight: 800; color: #155724; }
    .profit-display.loss { background: linear-gradient(135deg, #f8d7da, #f5c6cb); border-left-color: #dc3545; }
    .profit-display.loss .label,
    .profit-display.loss .value { color: #721c24; }

    .save-btn {
        background: linear-gradient(135deg, #fd7e14, #e8590c);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(253,126,20,0.4);
        transition: all 0.3s;
    }
    .save-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(253,126,20,0.5); color: white; }

    .section-title { font-size: 13px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #f0f0f0; }

    .batch-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .batch-table thead { background: #343a40; color: white; }
    .batch-table thead th { padding: 12px; font-size: 12px; text-transform: uppercase; border: none; }
</style>
@endpush

@section('content')

{{-- Success/Error --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-check-circle"></i> <strong>{{ session('success') }}</strong>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif

{{-- Hero Stats --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="hero-card">
            <small>Today's Batches</small>
            <h2>{{ $todayBatches }}</h2>
            <small>Total Milk In: {{ number_format($todayMilkIn, 1) }} L</small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="stat-card green">
                    <p class="stat-value text-success">{{ $weekBatches }}</p>
                    <p class="stat-label">This Week's Batches</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card purple">
                    <p class="stat-value" style="color:#6f42c1;">{{ number_format($weekMilkIn, 1) }} L</p>
                    <p class="stat-label">Milk Processed (Week)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('milk-processings.store') }}" method="POST" id="processing_form">
    @csrf

    <div class="row">
        {{-- Left: Form --}}
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-body">

                    <div class="section-title">📦 Batch Setup</div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="small font-weight-bold">FARM *</label>
                            <select name="farm_id" class="form-control" required>
                                <option value="">-- Select Farm --</option>
                                @foreach($farms as $farm)
                                    <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>{{ $farm->farm_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small font-weight-bold">SECTION</label>
                            <select name="section_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small font-weight-bold">DATE *</label>
                            <input type="date" name="processing_date" class="form-control" value="{{ old('processing_date', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="section-title">🥛 Product</div>
                        <div>
                            @foreach(['Curd (দই)' => '🍶', 'Ghee (ঘি)' => '🧈', 'Paneer (পনির)' => '🧀', 'Butter (মাখন)' => '🧈', 'Sweet (মিষ্টি)' => '🍬', 'Other' => '📦'] as $product => $icon)
                                <div class="product-chip" data-product="{{ $product }}">
                                    {{ $icon }} {{ $product }}
                                </div>
                            @endforeach
                            <input type="hidden" name="product_name" id="product_input" value="{{ old('product_name') }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="section-title">📊 Quantity & Cost</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group-custom">
                                    <label>Input Milk</label>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <input type="number" step="0.01" name="input_milk_liters" id="input_milk" class="form-control" value="{{ old('input_milk_liters') }}" placeholder="0.00" required>
                                        <span class="unit-label ml-2">L</span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="badge badge-success" id="available_milk_badge">
                                            🥛 Available: <strong>{{ number_format($availableMilk, 2) }} L</strong>
                                            @ avg <strong>{{ number_format($avgMilkCost, 2) }} BDT/L</strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group-custom">
                                    <label>Output Quantity</label>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <input type="number" step="0.01" name="output_quantity" id="output_qty" class="form-control" value="{{ old('output_quantity') }}" placeholder="0.00" required>
                                        <select name="unit" id="unit_select" class="form-control ml-2" style="width: 80px; font-size: 14px;">
                                            <option value="kg">kg</option>
                                            <option value="liter">liter</option>
                                            <option value="piece">piece</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group-custom">
                                    <label>Milk Cost (auto)</label>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <input type="number" step="0.01" name="milk_cost" id="milk_cost" class="form-control" value="{{ old('milk_cost', 0) }}">
                                        <span class="unit-label ml-2">BDT</span>
                                    </div>
                                    <small class="text-muted">@ {{ $avgMilkPrice }} BDT/L × Input</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group-custom">
                                    <label>Processing Cost</label>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <input type="number" step="0.01" name="processing_cost" id="proc_cost" class="form-control" value="{{ old('processing_cost', 0) }}">
                                        <span class="unit-label ml-2">BDT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group-custom">
                                    <label>Selling Price/Unit</label>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <input type="number" step="0.01" name="selling_price_per_unit" id="sell_price" class="form-control" value="{{ old('selling_price_per_unit', 0) }}">
                                        <span class="unit-label ml-2">BDT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="small font-weight-bold">NOTES</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                    </div>

                </div>
                <div class="card-footer text-right" style="background: white; border-top: 2px solid #f0f0f0;">
                    <a href="{{ route('milk-processings.index') }}" class="btn btn-light mr-2">Cancel</a>
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i> Save Processing Batch
                    </button>
                </div>
            </div>
        </div>

        {{-- Right: Live Calculation --}}
        <div class="col-md-4">
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="section-title">💰 Live Calculation</div>

                    <div class="profit-display mb-3">
                        <div class="label">Estimated Revenue</div>
                        <div class="value"><span id="revenue_display">0.00</span> <small>BDT</small></div>
                    </div>

                    <table class="table table-sm">
                        <tr>
                            <td>Milk Cost:</td>
                            <td class="text-right"><strong id="mc_display">0.00</strong> BDT</td>
                        </tr>
                        <tr>
                            <td>Processing Cost:</td>
                            <td class="text-right"><strong id="pc_display">0.00</strong> BDT</td>
                        </tr>
                        <tr class="bg-light">
                            <td><strong>Total Cost:</strong></td>
                            <td class="text-right"><strong id="tc_display">0.00</strong> BDT</td>
                        </tr>
                    </table>

                    <div class="profit-display" id="profit_box">
                        <div class="label">Estimated Profit</div>
                        <div class="value"><span id="profit_display">0.00</span> <small>BDT</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Previous Batches --}}
@if($previousBatches->count() > 0)
<div class="mt-4">
    <div class="section-title">📋 Recent Batches</div>
    <div class="batch-table">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th class="text-right">Input Milk</th>
                    <th class="text-right">Output</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right">Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previousBatches as $batch)
                <tr>
                    <td>{{ $batch->processing_date->format('d M') }}</td>
                    <td><strong>{{ $batch->product_name }}</strong></td>
                    <td class="text-right">{{ number_format($batch->input_milk_liters, 2) }} L</td>
                    <td class="text-right">{{ number_format($batch->output_quantity, 2) }} {{ $batch->unit }}</td>
                    <td class="text-right">{{ number_format($batch->total_value, 2) }}</td>
                    <td class="text-right {{ $batch->profit >= 0 ? 'text-success' : 'text-danger' }}">
                        <strong>{{ number_format($batch->profit, 2) }} BDT</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    const MILK_PRICE = {{ $avgMilkCost }};  // 👈 average cost

    // Product chip selection
    document.querySelectorAll('.product-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.product-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('product_input').value = this.dataset.product;
        });
    });

    // If old value — pre-select
    const oldProduct = document.getElementById('product_input').value;
    if (oldProduct) {
        document.querySelectorAll('.product-chip').forEach(c => {
            if (c.dataset.product === oldProduct) c.classList.add('active');
        });
    }

    // Input calculations
    const inputMilk = document.getElementById('input_milk');
    const outputQty = document.getElementById('output_qty');
    const milkCost = document.getElementById('milk_cost');
    const procCost = document.getElementById('proc_cost');
    const sellPrice = document.getElementById('sell_price');

    function recalc() {
        const milkLiters = parseFloat(inputMilk.value) || 0;
        const proc = parseFloat(procCost.value) || 0;
        const sellP = parseFloat(sellPrice.value) || 0;
        const output = parseFloat(outputQty.value) || 0;

        // Auto milk cost
        if (!milkCost.dataset.manual) {
            milkCost.value = (milkLiters * MILK_PRICE).toFixed(2);
        }
        const mc = parseFloat(milkCost.value) || 0;

        const totalCost = mc + proc;
        const revenue = output * sellP;
        const profit = revenue - totalCost;

        document.getElementById('mc_display').textContent = mc.toFixed(2);
        document.getElementById('pc_display').textContent = proc.toFixed(2);
        document.getElementById('tc_display').textContent = totalCost.toFixed(2);
        document.getElementById('revenue_display').textContent = revenue.toFixed(2);
        document.getElementById('profit_display').textContent = profit.toFixed(2);

        const profitBox = document.getElementById('profit_box');
        if (profit < 0) {
            profitBox.classList.add('loss');
        } else {
            profitBox.classList.remove('loss');
        }
    }

    inputMilk.addEventListener('input', recalc);
    outputQty.addEventListener('input', recalc);
    procCost.addEventListener('input', recalc);
    sellPrice.addEventListener('input', recalc);
    milkCost.addEventListener('input', function() {
        this.dataset.manual = 'true';
        recalc();
    });

    recalc();
</script>
@endpush