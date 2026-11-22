@extends('layouts.master')

@section('title', 'Goods Receipt')
@section('page-title', 'Goods Receipt')
@section('breadcrumb', 'Procurement / Purchase Orders / Receive')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Receive Goods for: {{ $purchaseOrder->po_number }}</h3>
        <div class="card-tools">
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Back to PO
            </a>
        </div>
    </div>
    <form action="{{ route('purchase-orders.receive.store', $purchaseOrder) }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Supplier</label>
                    <input type="text" class="form-control" value="{{ $purchaseOrder->supplier->supplier_name }}" disabled>
                </div>
                <div class="col-md-4">
                    <label>GR Number <span class="text-danger">*</span></label>
                    <input type="text" name="gr_number" class="form-control" value="{{ old('gr_number', $grNumber) }}" required>
                </div>
                <div class="col-md-4">
                    <label>Received Date <span class="text-danger">*</span></label>
                    <input type="date" name="received_date" class="form-control" value="{{ old('received_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <hr>
            <h5>Items Received</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Unit Price</th>
                        <th>Ordered</th>
                        <th>Already Received</th>
                        <th>Remaining</th>
                        <th width="150px">Receive Now <span class="text-danger">*</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->lines as $index => $line)
                    @php $remaining = $line->quantity - $line->received_quantity; @endphp
                    <tr>
                        <td>
                            <strong>{{ $line->item->item_code }}</strong><br>
                            <small>{{ $line->item->item_name }}</small>
                            <input type="hidden" name="items[{{ $index }}][purchase_order_line_id]" value="{{ $line->id }}">
                        </td>
                        <td>{{ number_format($line->unit_price, 2) }} BDT</td>
                        <td>{{ number_format($line->quantity, 2) }} {{ $line->item->unit }}</td>
                        <td>{{ number_format($line->received_quantity, 2) }} {{ $line->item->unit }}</td>
                        <td>
                            <strong class="text-{{ $remaining > 0 ? 'warning' : 'success' }}">
                                {{ number_format($remaining, 2) }} {{ $line->item->unit }}
                            </strong>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="items[{{ $index }}][received_quantity]"
                                   class="form-control"
                                   value="{{ old('items.'.$index.'.received_quantity', $remaining > 0 ? $remaining : 0) }}"
                                   min="0" max="{{ $remaining }}" {{ $remaining <= 0 ? 'disabled' : '' }}>
                            @if($remaining <= 0)
                                <small class="text-success">✅ Fully received</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Note:</strong> Saving will auto-create Stock Movement (In) and update Inventory stock.
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Save Goods Receipt
            </button>
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection