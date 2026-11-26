<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sale Invoice - {{ $animalSale->animal->animal_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 30px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }

        .header { display: flex; justify-content: space-between; border-bottom: 3px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #007bff; font-size: 26px; }
        .header .company-info { font-size: 12px; color: #666; margin-top: 5px; line-height: 1.6; }
        .header .invoice-title { text-align: right; }
        .header .invoice-title h2 { font-size: 26px; color: #333; }
        .header .invoice-title p { font-size: 13px; color: #666; margin-top: 3px; }

        .info { display: flex; justify-content: space-between; margin-bottom: 25px; }
        .info div { width: 48%; }
        .info h4 { font-size: 11px; color: #666; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .info p { font-size: 13px; line-height: 1.7; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #343a40; color: white; padding: 10px; text-align: left; font-size: 12px; text-transform: uppercase; }
        td { padding: 10px; border: 1px solid #ddd; font-size: 13px; }
        .right { text-align: right; }

        .summary { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .summary table { width: 350px; }
        .summary td { padding: 8px 12px; }
        .summary .total-row { background: #f0f0f0; font-weight: bold; font-size: 16px; }
        .summary .paid-row { color: #28a745; }
        .summary .due-row { color: #dc3545; font-weight: bold; }

        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 12px; font-size: 12px; color: white; font-weight: bold; }
        .status-paid { background: #28a745; }
        .status-partial { background: #ffc107; color: #333; }
        .status-unpaid { background: #dc3545; }

        .note { background: #f8f9fa; padding: 10px; border-left: 3px solid #007bff; font-size: 12px; margin-bottom: 20px; }

        .signature { display: flex; justify-content: space-between; margin-top: 60px; }
        .signature div { border-top: 1px solid #333; width: 220px; text-align: center; padding-top: 8px; font-size: 12px; }

        .footer { margin-top: 40px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #ddd; padding-top: 15px; }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 12px 25px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            🖨️ Print / Save as PDF
        </button>
        <a href="{{ route('animal-sales.show', $animalSale) }}" style="padding: 12px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            ← Back
        </a>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <h1>FarmNexa</h1>
            <div class="company-info">
                {{ $animalSale->animal->farm->farm_name ?? 'Farm Name' }}<br>
                {{ $animalSale->animal->farm->location ?? '' }}<br>
                @if($animalSale->animal->farm->phone ?? null)
                    Phone: {{ $animalSale->animal->farm->phone }}<br>
                @endif
                @if($animalSale->animal->farm->email ?? null)
                    Email: {{ $animalSale->animal->farm->email }}
                @endif
            </div>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>#SL-{{ str_pad($animalSale->id, 4, '0', STR_PAD_LEFT) }}</strong></p>
            <p>Date: {{ \Carbon\Carbon::parse($animalSale->sale_date)->format('d M, Y') }}</p>
            <p style="margin-top: 8px;">
                <span class="status-badge status-{{ $animalSale->proceeds_received >= $animalSale->selling_price ? 'paid' : ($animalSale->proceeds_received > 0 ? 'partial' : 'unpaid') }}">
                    {{ $animalSale->proceeds_received >= $animalSale->selling_price ? 'PAID' : ($animalSale->proceeds_received > 0 ? 'PARTIAL' : 'UNPAID') }}
                </span>
            </p>
        </div>
    </div>

    {{-- Buyer & Animal Info --}}
    <div class="info">
        <div>
            <h4>Bill To</h4>
            <p>
                <strong>{{ $animalSale->customer_name }}</strong><br>
                @if($animalSale->customer_phone)
                    Phone: {{ $animalSale->customer_phone }}<br>
                @endif
            </p>
        </div>
        <div style="text-align: right;">
            <h4>Sold By</h4>
            <p>
                <strong>{{ $animalSale->animal->farm->farm_name ?? 'Farm' }}</strong><br>
                {{ $animalSale->animal->farm->location ?? '' }}<br>
            </p>
        </div>
    </div>

    {{-- Items --}}
    <table>
        <thead>
            <tr>
                <th width="40">#</th>
                <th>Description</th>
                <th width="120" class="right">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <strong>{{ ucfirst($animalSale->animal->animal_type) }}</strong>
                    — Animal Code: <strong>{{ $animalSale->animal->animal_code }}</strong><br>
                    <small style="color: #666;">
                        @if($animalSale->animal->breed)
                            Breed: {{ $animalSale->animal->breed }} |
                        @endif
                        @if($animalSale->animal->sex)
                            Sex: {{ ucfirst($animalSale->animal->sex) }} |
                        @endif
                        @if($animalSale->animal->date_of_birth)
                            DOB: {{ $animalSale->animal->date_of_birth }}
                        @endif
                    </small>
                </td>
                <td class="right"><strong>{{ number_format($animalSale->selling_price, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Summary --}}
    <div class="summary">
        <table>
            <tr class="total-row">
                <td>TOTAL:</td>
                <td class="right">{{ number_format($animalSale->selling_price, 2) }} BDT</td>
            </tr>
            <tr class="paid-row">
                <td>Paid:</td>
                <td class="right">{{ number_format($animalSale->proceeds_received, 2) }} BDT</td>
            </tr>
            <tr class="due-row">
                <td>Due:</td>
                <td class="right">{{ number_format($animalSale->selling_price - $animalSale->proceeds_received, 2) }} BDT</td>
            </tr>
        </table>
    </div>

    {{-- Notes --}}
    @if($animalSale->notes)
    <div class="note">
        <strong>Notes:</strong> {{ $animalSale->notes }}
    </div>
    @endif

    {{-- Terms --}}
    <div class="note" style="background: #fff; border-left: 3px solid #28a745;">
        <strong>Thank you for your purchase!</strong>
        <br>
        This animal has been sold and transferred to the buyer. Any further maintenance, healthcare, or liability is the buyer's responsibility.
    </div>

    {{-- Signatures --}}
    <div class="signature">
        <div>Buyer Signature</div>
        <div>Seller Signature</div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>This is a computer-generated invoice. Generated on {{ now()->format('d M, Y h:i A') }}</p>
    </div>

</div>
</body>
</html>