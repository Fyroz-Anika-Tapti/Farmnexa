<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sale Invoice - {{ $animalSale->animal->animal_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 30px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #007bff; font-size: 24px; }
        .header h2 { font-size: 22px; text-align: right; }
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info div { width: 48%; }
        .info h4 { font-size: 12px; color: #666; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f0f0f0; padding: 8px; text-align: left; border: 1px solid #ddd; font-size: 12px; }
        td { padding: 8px; border: 1px solid #ddd; font-size: 13px; }
        .right { text-align: right; }
        .summary { display: flex; justify-content: flex-end; }
        .summary table { width: 350px; }
        .total { background: #f0f0f0; font-weight: bold; font-size: 15px; }
        .profit { background: #d4edda; color: #155724; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; color: #666; font-size: 11px; border-top: 1px solid #ddd; padding-top: 10px; }
        .signature { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature div { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 5px; }
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body>
<div class="container">

    <div class="no-print" style="text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print / Save as PDF
        </button>
        <a href="{{ route('animal-sales.show', $animalSale) }}" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">← Back</a>
    </div>

    <div class="header">
        <div>
            <h1>FarmNexa</h1>
            <p style="font-size: 12px; color: #666;">
                {{ $animalSale->animal->farm->farm_name ?? 'Farm' }}<br>
                {{ $animalSale->animal->farm->location ?? '' }}<br>
                Phone: {{ $animalSale->animal->farm->phone ?? '-' }}
            </p>
        </div>
        <div style="text-align: right;">
            <h2>ANIMAL SALE</h2>
            <p style="font-size: 12px; color: #666;">Date: {{ $animalSale->sale_date->format('d M, Y') }}</p>
        </div>
    </div>

    <div class="info">
        <div>
            <h4>BUYER</h4>
            <p><strong>{{ $animalSale->customer_name }}</strong></p>
            @if($animalSale->customer_phone)
                <p style="font-size: 12px; color: #666;">{{ $animalSale->customer_phone }}</p>
            @endif
        </div>
        <div style="text-align: right;">
            <h4>ANIMAL</h4>
            <p><strong>{{ $animalSale->animal->animal_code }}</strong></p>
            <p style="font-size: 12px; color: #666;">{{ ucfirst($animalSale->animal->animal_type) }} — {{ $animalSale->animal->breed ?? '-' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Purchase Value</td>
                <td class="right">{{ number_format($animalSale->animal->purchase_value ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Feed Cost</td>
                <td class="right">{{ number_format($animalSale->animal->total_feed_cost, 2) }}</td>
            </tr>
            <tr>
                <td>Medicine Cost</td>
                <td class="right">{{ number_format($animalSale->animal->total_medicine_cost, 2) }}</td>
            </tr>
            <tr>
                <td>Other Expenses</td>
                <td class="right">{{ number_format($animalSale->animal->total_other_expense, 2) }}</td>
            </tr>
            <tr style="background: #f0f0f0;">
                <td><strong>Total Carrying Value</strong></td>
                <td class="right"><strong>{{ number_format($animalSale->carrying_value, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Profit %:</td>
                <td class="right">{{ number_format($animalSale->profit_percentage ?? 0, 2) }}%</td>
            </tr>
            <tr class="total">
                <td>Selling Price:</td>
                <td class="right">{{ number_format($animalSale->selling_price, 2) }} BDT</td>
            </tr>
            <tr>
                <td>Proceeds Received:</td>
                <td class="right">{{ number_format($animalSale->proceeds_received, 2) }} BDT</td>
            </tr>
            <tr class="profit">
                <td>PROFIT:</td>
                <td class="right">{{ number_format($animalSale->profit_amount, 2) }} BDT</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div>Buyer Signature</div>
        <div>Seller Signature</div>
    </div>

    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p>Generated on {{ now()->format('d M, Y h:i A') }}</p>
    </div>

</div>
</body>
</html>