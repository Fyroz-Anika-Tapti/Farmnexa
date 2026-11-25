@extends('layouts.master')

@section('title', 'Bulk Feed Entry')
@section('page-title', 'Bulk Feed Entry')
@section('breadcrumb', 'Animals / Bulk Feed')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-utensils"></i> Feed Multiple Animals</h3>
        <div class="card-tools">
            <a href="{{ route('animals.index') }}" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <form action="{{ route('animals.feeds.bulk.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif

            @if($items->isEmpty() || $animals->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    @if($items->isEmpty()) No feed items in inventory with stock. @endif
                    @if($animals->isEmpty()) No active animals. @endif
                </div>
            @else

            {{-- Feed Details --}}
            <div class="row mb-4">
                <div class="col-md-4 form-group">
                    <label>Feed Item <span class="text-danger">*</span></label>
                    <select name="inventory_item_id" id="item_select" class="form-control" required>
                        <option value="">-- Select Feed --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}"
                                    data-price="{{ $item->purchase_price }}"
                                    data-stock="{{ $item->current_stock }}"
                                    data-unit="{{ $item->unit }}"
                                    {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->item_name }} ({{ number_format($item->current_stock, 2) }} {{ $item->unit }} @ {{ number_format($item->purchase_price, 2) }} BDT)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="stock_help"></small>
                </div>
                <div class="col-md-4 form-group">
                    <label>Feed Date <span class="text-danger">*</span></label>
                    <input type="date" name="feed_date" class="form-control" value="{{ old('feed_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Quantity per animal <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="quantity" id="qty_input" class="form-control" value="{{ old('quantity', 1) }}" required>
                        <div class="input-group-append"><span class="input-group-text" id="unit_label">unit</span></div>
                    </div>
                    <small class="text-muted" id="total_help"></small>
                </div>
            </div>

            {{-- Select Animals --}}
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-cow"></i> Select Animals ({{ $animals->count() }} active)
                    </h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" id="select_all">
                            <i class="fas fa-check-double"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" id="deselect_all">
                            <i class="fas fa-times"></i> Deselect All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($animals as $animal)
                        <div class="col-md-4 col-lg-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="animal_ids[]" value="{{ $animal->id }}"
                                       class="custom-control-input animal-checkbox" id="animal_{{ $animal->id }}"
                                       {{ in_array($animal->id, old('animal_ids', [])) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="animal_{{ $animal->id }}">
                                    <strong>{{ $animal->animal_code }}</strong><br>
                                    <small class="text-muted">{{ ucfirst($animal->animal_type) }} | {{ $animal->breed ?? '-' }}</small>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <strong>Selected: <span id="selected_count">0</span> animals</strong>
                    <span class="ml-3">Total Quantity: <span id="total_qty">0</span></span>
                    <span class="ml-3">Total Cost: <span id="total_cost">0.00</span> BDT</span>
                </div>
            </div>

            {{-- Notes --}}
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-lg" {{ $items->isEmpty() || $animals->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-save"></i> Save Bulk Feed
            </button>
            <a href="{{ route('animals.index') }}" class="btn btn-default btn-lg">Cancel</a>
        </div>
    </form>
</div>

<script>
    const itemSelect = document.getElementById('item_select');
    const qtyInput   = document.getElementById('qty_input');
    const stockHelp  = document.getElementById('stock_help');
    const unitLabel  = document.getElementById('unit_label');
    const totalHelp  = document.getElementById('total_help');
    const selectedCount = document.getElementById('selected_count');
    const totalQty   = document.getElementById('total_qty');
    const totalCost  = document.getElementById('total_cost');

    function recalc() {
        const opt = itemSelect.options[itemSelect.selectedIndex];
        const price = opt && opt.dataset.price ? parseFloat(opt.dataset.price) : 0;
        const stock = opt && opt.dataset.stock ? parseFloat(opt.dataset.stock) : 0;
        const unit  = opt && opt.dataset.unit ? opt.dataset.unit : 'unit';
        const qty   = parseFloat(qtyInput.value) || 0;

        unitLabel.textContent = unit;
        stockHelp.textContent = 'Available: ' + stock.toFixed(2) + ' ' + unit;

        const selected = document.querySelectorAll('.animal-checkbox:checked').length;
        const tQty = qty * selected;
        const tCost = tQty * price;

        selectedCount.textContent = selected;
        totalQty.textContent = tQty.toFixed(2) + ' ' + unit;
        totalCost.textContent = tCost.toFixed(2);

        if (tQty > stock && stock > 0) {
            totalHelp.textContent = '⚠️ Not enough stock!';
            totalHelp.classList.add('text-danger');
        } else {
            totalHelp.textContent = '';
            totalHelp.classList.remove('text-danger');
        }
    }

    itemSelect.addEventListener('change', recalc);
    qtyInput.addEventListener('input', recalc);

    document.querySelectorAll('.animal-checkbox').forEach(cb => {
        cb.addEventListener('change', recalc);
    });

    document.getElementById('select_all').addEventListener('click', function() {
        document.querySelectorAll('.animal-checkbox').forEach(cb => cb.checked = true);
        recalc();
    });

    document.getElementById('deselect_all').addEventListener('click', function() {
        document.querySelectorAll('.animal-checkbox').forEach(cb => cb.checked = false);
        recalc();
    });

    recalc();
</script>
@endsection