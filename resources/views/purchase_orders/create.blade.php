@extends('layouts.master')

@section('title', 'New Purchase Order')
@section('page-title', 'New Purchase Order')
@section('breadcrumb', 'Procurement / Purchase Orders / Create')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">PO Details</h3></div>
    <form action="{{ route('purchase-orders.store') }}" method="POST" id="po_form">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control" required>
                        <option value="">-- Select Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>{{ $farm->farm_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>PO Number <span class="text-danger">*</span></label>
                    <input type="text" name="po_number" class="form-control" value="{{ old('po_number', 'PO-' . date('YmdHis')) }}" required>
                </div>

                <div class="col-md-4 form-group">
                    <label>PO Date <span class="text-danger">*</span></label>
                    <input type="date" name="po_date" class="form-control" value="{{ old('po_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Expected Delivery</label>
                    <input type="date" name="expected_delivery_date" class="form-control" value="{{ old('expected_delivery_date') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                </div>
            </div>

            <hr>
            <h5>Items</h5>
            <table class="table table-bordered" id="items_table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th width="120px">Quantity</th>
                        <th width="120px">Unit Price</th>
                        <th width="120px">Line Total</th>
                        <th width="50px"></th>
                    </tr>
                </thead>
                <tbody id="items_body"></tbody>
            </table>
            <button type="button" class="btn btn-sm btn-info" id="add_row_btn">
                <i class="fas fa-plus"></i> Add Item
            </button>

            <hr>
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <table class="table">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td id="subtotal_display">0.00 BDT</td>
                        </tr>
                        <tr>
                            <td>Tax:</td>
                            <td><input type="number" step="0.01" name="tax_amount" id="tax_input" class="form-control form-control-sm" value="{{ old('tax_amount', 0) }}"></td>
                        </tr>
                        <tr>
                            <td>Discount:</td>
                            <td><input type="number" step="0.01" name="discount_amount" id="discount_input" class="form-control form-control-sm" value="{{ old('discount_amount', 0) }}"></td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td id="total_display"><strong>0.00 BDT</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save PO</button>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

<script>
    const items = @json($items);
    let rowIndex = 0;

    function addRow() {
        const row = `
            <tr>
                <td>
                    <select name="items[${rowIndex}][inventory_item_id]" class="form-control item-select" required>
                        <option value="">-- Select Item --</option>
                        ${items.map(i => `<option value="${i.id}" data-price="${i.purchase_price}">${i.item_code} - ${i.item_name}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" step="0.01" name="items[${rowIndex}][quantity]" class="form-control qty-input" value="1" min="0.01" required></td>
                <td><input type="number" step="0.01" name="items[${rowIndex}][unit_price]" class="form-control price-input" value="0" min="0" required></td>
                <td><span class="line-total">0.00</span> BDT</td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
            </tr>
        `;
        document.getElementById('items_body').insertAdjacentHTML('beforeend', row);
        rowIndex++;
    }

    document.getElementById('add_row_btn').addEventListener('click', addRow);

    document.getElementById('items_body').addEventListener('input', function(e) {
        if (e.target.classList.contains('item-select')) {
            const price = e.target.options[e.target.selectedIndex].dataset.price;
            if (price) {
                e.target.closest('tr').querySelector('.price-input').value = price;
            }
            recalc();
        } else if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            recalc();
        }
    });

    document.getElementById('items_body').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
            recalc();
        }
    });

    document.getElementById('tax_input').addEventListener('input', recalc);
    document.getElementById('discount_input').addEventListener('input', recalc);

    function recalc() {
        let subtotal = 0;
        document.querySelectorAll('#items_body tr').forEach(tr => {
            const qty   = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const price = parseFloat(tr.querySelector('.price-input').value) || 0;
            const line  = qty * price;
            tr.querySelector('.line-total').textContent = line.toFixed(2);
            subtotal += line;
        });

        const tax      = parseFloat(document.getElementById('tax_input').value) || 0;
        const discount = parseFloat(document.getElementById('discount_input').value) || 0;
        const total    = subtotal + tax - discount;

        document.getElementById('subtotal_display').textContent = subtotal.toFixed(2) + ' BDT';
        document.getElementById('total_display').innerHTML = '<strong>' + total.toFixed(2) + ' BDT</strong>';
    }

    addRow();
</script>
@endsection