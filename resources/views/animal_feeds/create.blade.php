@extends('layouts.master')

@section('title', 'Add Feed')
@section('page-title', 'Add Feed')
@section('breadcrumb', 'Animals / Feed / Add')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Feed for: {{ $animal->animal_code }}</h3>
    </div>
    <form action="{{ route('animals.feeds.store', $animal) }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif

            @if($items->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No feed items in inventory with available stock.
                    <a href="{{ route('inventory-items.create') }}">Add inventory item</a>
                </div>
            @else
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Feed Item <span class="text-danger">*</span></label>
                    <select name="inventory_item_id" id="item_select" class="form-control" required>
                        <option value="">-- Select Feed --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}"
                                    data-price="{{ $item->purchase_price }}"
                                    data-stock="{{ $item->current_stock }}"
                                    data-unit="{{ $item->unit }}"
                                    {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->item_name }} ({{ number_format($item->current_stock, 2) }} {{ $item->unit }} @ {{ number_format($item->purchase_price, 2) }} BDT/{{ $item->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Feed Date <span class="text-danger">*</span></label>
                    <input type="date" name="feed_date" class="form-control" value="{{ old('feed_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Quantity <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="quantity" id="qty_input" class="form-control" value="{{ old('quantity') }}" required>
                        <div class="input-group-append"><span class="input-group-text" id="unit_label">unit</span></div>
                    </div>
                    <small class="text-muted" id="stock_help"></small>
                </div>
                <div class="col-md-4 form-group">
                    <label>Unit Price (auto)</label>
                    <input type="text" id="price_display" class="form-control" readonly value="0.00 BDT">
                </div>
                <div class="col-md-4 form-group">
                    <label>Total Cost (auto)</label>
                    <input type="text" id="total_display" class="form-control" readonly value="0.00 BDT">
                </div>
                <div class="col-md-12 form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary" {{ $items->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-save"></i> Save Feed
            </button>
            <a href="{{ route('animals.feeds.index', $animal) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

<script>
    const itemSelect = document.getElementById('item_select');
    if (itemSelect) {
        const qtyInput   = document.getElementById('qty_input');
        const priceDisplay = document.getElementById('price_display');
        const totalDisplay = document.getElementById('total_display');
        const unitLabel    = document.getElementById('unit_label');
        const stockHelp    = document.getElementById('stock_help');

        function recalc() {
            const opt = itemSelect.options[itemSelect.selectedIndex];
            if (!opt || !opt.dataset.price) {
                priceDisplay.value = '0.00 BDT';
                totalDisplay.value = '0.00 BDT';
                stockHelp.textContent = '';
                return;
            }
            const price = parseFloat(opt.dataset.price);
            const qty   = parseFloat(qtyInput.value) || 0;
            const unit  = opt.dataset.unit;
            const stock = parseFloat(opt.dataset.stock);

            unitLabel.textContent = unit;
            priceDisplay.value = price.toFixed(2) + ' BDT';
            totalDisplay.value = (price * qty).toFixed(2) + ' BDT';
            stockHelp.textContent = 'Available: ' + stock.toFixed(2) + ' ' + unit;
        }
        itemSelect.addEventListener('change', recalc);
        qtyInput.addEventListener('input', recalc);
        recalc();
    }
</script>
@endsection