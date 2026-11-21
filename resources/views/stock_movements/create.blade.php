@extends('layouts.master')

@section('title', 'Add Stock Movement')
@section('page-title', 'Add Stock Movement')
@section('breadcrumb', 'Inventory / Stock Movements / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stock Movement Details</h3>
    </div>
    <form action="{{ route('stock-movements.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        <option value="">-- Select Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>
                                {{ $farm->farm_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Movement Date <span class="text-danger">*</span></label>
                    <input type="date" name="movement_date" class="form-control" value="{{ old('movement_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Item <span class="text-danger">*</span></label>
                    <select name="inventory_item_id" id="item_select" class="form-control" required>
                        <option value="">-- Select Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}"
                                    data-stock="{{ $item->current_stock }}"
                                    data-unit="{{ $item->unit }}"
                                    data-price="{{ $item->purchase_price }}"
                                    {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->item_code }} - {{ $item->item_name }} ({{ number_format($item->current_stock, 2) }} {{ $item->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Movement Type <span class="text-danger">*</span></label>
                    <select name="movement_type" id="type_select" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        <option value="in"         {{ old('movement_type') == 'in' ? 'selected' : '' }}>In (Stock Increase)</option>
                        <option value="out"        {{ old('movement_type') == 'out' ? 'selected' : '' }}>Out (Stock Decrease)</option>
                        <option value="adjustment" {{ old('movement_type') == 'adjustment' ? 'selected' : '' }}>Adjustment (Set New Stock)</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="quantity" id="quantity_input" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required>
                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                    <small class="text-muted" id="quantity_help"></small>
                </div>
                <div class="col-md-4 form-group">
                    <label>Unit Price (BDT)</label>
                    <input type="number" step="0.01" name="unit_price" id="price_input" class="form-control" value="{{ old('unit_price', '0.00') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Total Value (auto)</label>
                    <input type="text" id="total_value_display" class="form-control" value="0.00" readonly disabled>
                </div>

                <div class="col-md-6 form-group">
                    <label>Reference Type</label>
                    <select name="reference_type" class="form-control">
                        <option value="">-- None --</option>
                        <option value="purchase"   {{ old('reference_type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="issue"      {{ old('reference_type') == 'issue' ? 'selected' : '' }}>Issue</option>
                        <option value="adjustment" {{ old('reference_type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Reference No</label>
                    <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}" placeholder="e.g. PO-001, Issue-101">
                </div>

                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Movement</button>
            <a href="{{ route('stock-movements.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

<script>
    const itemSelect = document.getElementById('item_select');
    const typeSelect = document.getElementById('type_select');
    const qtyInput = document.getElementById('quantity_input');
    const priceInput = document.getElementById('price_input');
    const totalDisplay = document.getElementById('total_value_display');
    const helpText = document.getElementById('quantity_help');

    function updateHelp() {
        const opt = itemSelect.options[itemSelect.selectedIndex];
        if (!opt || !opt.dataset.stock) {
            helpText.textContent = '';
            return;
        }
        const stock = parseFloat(opt.dataset.stock);
        const unit = opt.dataset.unit;
        const type = typeSelect.value;

        if (type === 'out') {
            helpText.textContent = 'Available: ' + stock.toFixed(2) + ' ' + unit;
        } else if (type === 'adjustment') {
            helpText.textContent = 'Current: ' + stock.toFixed(2) + ' ' + unit + ' (enter new total)';
        } else {
            helpText.textContent = 'Current: ' + stock.toFixed(2) + ' ' + unit;
        }

        if (opt.dataset.price && !priceInput.value) {
            priceInput.value = opt.dataset.price;
        }
    }

    function updateTotal() {
        const qty = parseFloat(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalDisplay.value = (qty * price).toFixed(2);
    }

    itemSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (opt && opt.dataset.price) {
            priceInput.value = opt.dataset.price;
        }
        updateHelp();
        updateTotal();
    });
    typeSelect.addEventListener('change', updateHelp);
    qtyInput.addEventListener('input', updateTotal);
    priceInput.addEventListener('input', updateTotal);

    updateHelp();
    updateTotal();
</script>
@endsection