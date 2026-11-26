@extends('layouts.master')

@section('title', 'Record Animal Sale')
@section('page-title', 'Record Animal Sale')
@section('breadcrumb', 'Livestock / Sales / Create')

@section('content')
<form action="{{ route('animal-sales.store') }}" method="POST">
    @csrf
    <div class="row">

        {{-- Left: Form --}}
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tag"></i> Sale Details</h3>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Animal <span class="text-danger">*</span></label>
                        <select name="animal_id" id="animal_select" class="form-control form-control-lg" required>
                            <option value="">-- Select Animal --</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}" {{ old('animal_id') == $animal->id ? 'selected' : '' }}>
                                    {{ $animal->animal_code }} ({{ ucfirst($animal->animal_type) }}, {{ $animal->breed ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Customer Phone</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Sale Date <span class="text-danger">*</span></label>
                        <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                    </div>

                    <hr>
                    <h5><i class="fas fa-percentage"></i> Profit Setting</h5>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Profit Percentage (%)</label>
                            <input type="number" step="0.01" name="profit_percentage" id="profit_percent"
                                   class="form-control" value="{{ old('profit_percentage', 0) }}" placeholder="e.g. 20">
                            <small class="text-muted">What percentage profit do you want to make?</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Suggested Selling Price</label>
                            <input type="text" id="suggested_price_display" class="form-control" readonly value="0.00 BDT">
                            <small class="text-muted">Carrying Value × (1 + Profit%)</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Selling Price (BDT) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="selling_price" id="selling_price"
                                   class="form-control form-control-lg" value="{{ old('selling_price') }}" required>
                            <small class="text-muted">You can set your own desired selling price</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Proceeds Received (BDT)</label>
                            <input type="number" step="0.01" name="proceeds_received"
                                   class="form-control form-control-lg" value="{{ old('proceeds_received', 0) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check"></i> Confirm Sale
                    </button>
                    <a href="{{ route('animal-sales.index') }}" class="btn btn-default btn-lg">Cancel</a>
                </div>
            </div>
        </div>

        {{-- Right: Cost Breakdown --}}
        <div class="col-md-5">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calculator"></i> Cost Breakdown</h3>
                </div>
                <div class="card-body">
                    <div id="no_selection" class="alert alert-info">
                        Select an animal to see cost breakdown.
                    </div>

                    <div id="cost_section" style="display:none;">
                        <h5 class="text-primary" id="animal_label">-</h5>
                        <hr>
                        <table class="table table-sm">
                            <tr>
                                <td>Purchase Value:</td>
                                <td class="text-right" id="cv_purchase">0</td>
                            </tr>
                            <tr>
                                <td>Feed Cost:</td>
                                <td class="text-right" id="cv_feed">0</td>
                            </tr>
                            <tr>
                                <td>Medicine Cost:</td>
                                <td class="text-right" id="cv_medicine">0</td>
                            </tr>
                            <tr>
                                <td>Other Expenses:</td>
                                <td class="text-right" id="cv_other">0</td>
                            </tr>
                            <tr class="bg-warning">
                                <td><strong>CARRYING VALUE:</strong></td>
                                <td class="text-right"><strong id="cv_total">0</strong> BDT</td>
                            </tr>
                        </table>
                        <hr>
                        <div class="alert alert-success mb-0">
                            <h5 class="mb-1">PROFIT:</h5>
                            <h4 class="mb-0"><strong id="profit_display">0.00</strong> BDT</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection

@push('scripts')
<script>
    let carryingValue = 0;
    let manualPriceMode = false;  

    const animalSelect    = document.getElementById('animal_select');
    const profitPercent   = document.getElementById('profit_percent');
    const sellingPrice    = document.getElementById('selling_price');
    const suggestedDisplay = document.getElementById('suggested_price_display');
    const profitDisplay   = document.getElementById('profit_display');
    const costSection     = document.getElementById('cost_section');
    const noSelection     = document.getElementById('no_selection');

    // Animal select → cost load
    animalSelect.addEventListener('change', function() {
        const animalId = this.value;
        if (!animalId) {
            costSection.style.display = 'none';
            noSelection.style.display = 'block';
            return;
        }

        fetch(`/animal-sales/carrying-value/${animalId}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    animalSelect.value = '';
                    costSection.style.display = 'none';
                    noSelection.style.display = 'block';
                    return;
                }

                carryingValue = parseFloat(data.carrying_value);

                document.getElementById('animal_label').textContent = data.animal_code + ' (' + data.animal_type + ')';
                document.getElementById('cv_purchase').textContent = data.purchase_value.toFixed(2) + ' BDT';
                document.getElementById('cv_feed').textContent = data.feed_cost.toFixed(2) + ' BDT';
                document.getElementById('cv_medicine').textContent = data.medicine_cost.toFixed(2) + ' BDT';
                document.getElementById('cv_other').textContent = data.other_expense.toFixed(2) + ' BDT';
                document.getElementById('cv_total').textContent = carryingValue.toFixed(2);

                costSection.style.display = 'block';
                noSelection.style.display = 'none';

                // reset manual mode
                manualPriceMode = false;
                updateSuggestedAndProfit();
            })
            .catch(err => alert('Error: ' + err));
    });

    // Profit % পরিবর্তন → suggested price আপডেট + selling price auto-fill
    profitPercent.addEventListener('input', function() {
        // % পরিবর্তন হলে manual mode reset — auto-fill হবে
        manualPriceMode = false;
        updateSuggestedAndProfit();
    });

    // Selling Price — ইউজার নিজে type করলে manual mode on
    sellingPrice.addEventListener('input', function() {
        manualPriceMode = true;
        updateProfitOnly();
    });

    // Suggested price & selling price auto-fill
    function updateSuggestedAndProfit() {
        if (carryingValue <= 0) return;

        const percent = parseFloat(profitPercent.value) || 0;
        const suggested = carryingValue * (1 + percent / 100);
        suggestedDisplay.value = suggested.toFixed(2) + ' BDT';

        // manual mode না হলে auto-fill
        if (!manualPriceMode) {
            sellingPrice.value = suggested.toFixed(2);
        }

        updateProfitOnly();
    }

    // শুধু profit recalculate
    function updateProfitOnly() {
        if (carryingValue <= 0) return;

        const selling = parseFloat(sellingPrice.value) || 0;
        const profit = selling - carryingValue;

        profitDisplay.textContent = profit.toFixed(2);
        const alertBox = profitDisplay.closest('.alert');
        if (alertBox) {
            alertBox.className = 'alert mb-0 ' + (profit >= 0 ? 'alert-success' : 'alert-danger');
        }
    }
</script>
@endpush