@extends('layouts.master')

@section('title', 'New Sales Invoice')
@section('page-title', 'New Sales Invoice')
@section('breadcrumb', 'Sales / Invoices / Create')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
@endpush

@section('content')
<form action="{{ route('sales-invoices.store') }}" method="POST" id="invoice_form">
    @csrf

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice"></i> Invoice Information</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Farm <span class="text-danger">*</span></label>
                    <select name="farm_id" class="form-control select2" required>
                        <option value="">-- Select Farm --</option>
                        @foreach($farms as $farm)
                            <option value="{{ $farm->id }}" {{ old('farm_id') == $farm->id ? 'selected' : '' }}>{{ $farm->farm_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                        <option value="">-- Search Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-balance="{{ $c->current_balance }}" data-phone="{{ $c->phone }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->customer_name }} ({{ $c->customer_code }})
                            </option>
                        @endforeach
                    </select>
                    <small id="customer_info" class="text-muted"></small>
                </div>
                <div class="col-md-4 form-group">
                    <label>Invoice # <span class="text-danger">*</span></label>
                    <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $invoiceNumber) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Invoice Date <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-shopping-basket"></i> Items</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" id="add_row_btn">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="40px">#</th>
                        <th>Product</th>
                        <th width="120px">Stock</th>
                        <th width="140px">Quantity</th>
                        <th width="130px">Unit Price</th>
                        <th width="150px">Line Total</th>
                        <th width="50px"></th>
                    </tr>
                </thead>
                <tbody id="items_body"></tbody>
            </table>
        </div>
    </div>

    <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calculator"></i> Summary</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <table class="table table-sm">
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-right" id="subtotal_display"><strong>0.00 BDT</strong></td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td><input type="number" step="0.01" name="tax_amount" id="tax_input" class="form-control form-control-sm" value="{{ old('tax_amount', 0) }}"></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td><input type="number" step="0.01" name="discount_amount" id="discount_input" class="form-control form-control-sm" value="{{ old('discount_amount', 0) }}"></td>
                        </tr>
                        <tr class="bg-light">
                            <td><strong>Total</strong></td>
                            <td class="text-right" id="total_display"><strong class="text-primary">0.00 BDT</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Paid Now</strong></td>
                            <td><input type="number" step="0.01" name="paid_amount" id="paid_input" class="form-control form-control-sm" value="{{ old('paid_amount', 0) }}"></td>
                        </tr>
                        
                        <tr>
                            <td>Payment Method</td>
                            <td>
                                <select name="payment_method" class="form-control form-control-sm">
                                    <option value="cash"   {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                    <option value="bkash"  {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>📱 bKash</option>
                                    <option value="nagad"  {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>📱 Nagad</option>
                                    <option value="bank"   {{ old('payment_method') == 'bank' ? 'selected' : '' }}>🏦 Bank</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>📝 Cheque</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Reference No</td>
                            <td>
                                <input type="text" name="payment_reference" class="form-control form-control-sm"
                                    value="{{ old('payment_reference') }}" placeholder="Trx ID / Cheque #">
                            </td>
                        </tr>

                        <tr class="bg-danger-o">
                            <td><strong>Due</strong></td>
                            <td class="text-right" id="due_display"><strong class="text-danger">0.00 BDT</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Invoice</button>
            <a href="{{ route('sales-invoices.index') }}" class="btn btn-default btn-lg">Cancel</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const products = @json($products);
    let rowIndex = 0;

    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4', placeholder: '-- Select --', allowClear: true });
    });

    document.getElementById('customer_id').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const info = document.getElementById('customer_info');
        if (opt && opt.dataset.balance !== undefined) {
            const bal = parseFloat(opt.dataset.balance);
            info.innerHTML = `Current Due: <strong class="text-${bal > 0 ? 'danger' : 'success'}">${bal.toFixed(2)} BDT</strong>`;
        } else info.textContent = '';
    });

    function addRow() {
        const row = `
            <tr>
                <td class="text-center"><span class="row-num"></span></td>
                <td>
                    <select name="items[${rowIndex}][product_id]" class="form-control product-select" required>
                        <option value="">-- Select Product --</option>
                        ${products.map(p => `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.current_stock || 0}" data-unit="${p.unit}">${p.product_name} (${p.unit})</option>`).join('')}
                    </select>
                </td>
                <td class="text-center"><span class="stock-badge badge badge-secondary">--</span></td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" name="items[${rowIndex}][quantity]" class="form-control qty-input" value="1" min="0.01" required>
                        <div class="input-group-append"><span class="input-group-text unit-label">unit</span></div>
                    </div>
                </td>
                <td><input type="number" step="0.01" name="items[${rowIndex}][unit_price]" class="form-control price-input form-control-sm" value="0" required></td>
                <td class="text-right"><strong class="line-total">0.00</strong> BDT</td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
            </tr>
        `;
        document.getElementById('items_body').insertAdjacentHTML('beforeend', row);
        rowIndex++;
        renumber();
        recalc();
    }

    function renumber() {
        document.querySelectorAll('#items_body .row-num').forEach((el, i) => el.textContent = i + 1);
    }

    document.getElementById('add_row_btn').addEventListener('click', addRow);

    document.getElementById('items_body').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const tr = e.target.closest('tr');
            const opt = e.target.options[e.target.selectedIndex];
            if (opt && opt.dataset.price) {
                tr.querySelector('.price-input').value = opt.dataset.price;
                tr.querySelector('.unit-label').textContent = opt.dataset.unit || 'unit';
                const stock = parseFloat(opt.dataset.stock) || 0;
                const badge = tr.querySelector('.stock-badge');
                badge.className = 'stock-badge badge badge-' + (stock > 0 ? 'info' : 'warning');
                badge.textContent = stock.toFixed(2) + ' available';
            }
            recalc();
        }
    });

    document.getElementById('items_body').addEventListener('input', function(e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) recalc();
    });

    document.getElementById('items_body').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
            renumber();
            recalc();
        }
    });

    ['tax_input', 'discount_input', 'paid_input'].forEach(id => {
        document.getElementById(id).addEventListener('input', recalc);
    });

    function recalc() {
        let subtotal = 0;
        document.querySelectorAll('#items_body tr').forEach(tr => {
            const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const price = parseFloat(tr.querySelector('.price-input').value) || 0;
            const line = qty * price;
            tr.querySelector('.line-total').textContent = line.toFixed(2);
            subtotal += line;
        });

        const tax = parseFloat(document.getElementById('tax_input').value) || 0;
        const discount = parseFloat(document.getElementById('discount_input').value) || 0;
        const total = subtotal + tax - discount;
        const paid = parseFloat(document.getElementById('paid_input').value) || 0;
        const due = total - paid;

        document.getElementById('subtotal_display').innerHTML = '<strong>' + subtotal.toFixed(2) + ' BDT</strong>';
        document.getElementById('total_display').innerHTML = '<strong class="text-primary">' + total.toFixed(2) + ' BDT</strong>';
        document.getElementById('due_display').innerHTML = '<strong class="text-' + (due > 0 ? 'danger' : 'success') + '">' + due.toFixed(2) + ' BDT</strong>';
    }

    addRow();
</script>
@endpush