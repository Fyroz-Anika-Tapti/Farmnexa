@extends('layouts.master')

@section('title', 'Sale Details')
@section('page-title', 'Sale Details')
@section('breadcrumb', 'Livestock / Sales / View')

@section('content')
<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tag"></i> Sale Confirmation</h3>
        <div class="card-tools">
            <a href="{{ route('animal-sales.invoice', $animalSale) }}" target="_blank" class="btn btn-primary btn-sm">
                <i class="fas fa-print"></i> Print Invoice
            </a>
            <a href="{{ route('animal-sales.index') }}" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h5>Animal Info</h5>
                <p><strong>Code:</strong> {{ $animalSale->animal->animal_code }}</p>
                <p><strong>Type:</strong> {{ ucfirst($animalSale->animal->animal_type) }}</p>
                <p><strong>Breed:</strong> {{ $animalSale->animal->breed ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Sale Info</h5>
                <p><strong>Customer:</strong> {{ $animalSale->customer_name }}</p>
                <p><strong>Date:</strong> {{ $animalSale->sale_date->format('d M, Y') }}</p>
                <p><strong>Phone:</strong> {{ $animalSale->customer_phone ?? '-' }}</p>
            </div>
        </div>

        <hr>

        <h5>Cost & Profit</h5>
        <table class="table table-bordered">
            <tr>
                <td>Carrying Value</td>
                <td class="text-right">{{ number_format($animalSale->carrying_value, 2) }} BDT</td>
            </tr>
            <tr>
                <td>Profit Percentage</td>
                <td class="text-right">{{ number_format($animalSale->profit_percentage ?? 0, 2) }}%</td>
            </tr>
            <tr>
                <td>Selling Price</td>
                <td class="text-right"><strong>{{ number_format($animalSale->selling_price, 2) }} BDT</strong></td>
            </tr>
            <tr>
                <td>Proceeds Received</td>
                <td class="text-right">{{ number_format($animalSale->proceeds_received, 2) }} BDT</td>
            </tr>
            <tr class="{{ $animalSale->profit_amount >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                <td><strong>PROFIT / LOSS</strong></td>
                <td class="text-right"><strong>{{ number_format($animalSale->profit_amount, 2) }} BDT</strong></td>
            </tr>
        </table>
    </div>
</div>
@endsection