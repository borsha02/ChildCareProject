<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; }
        .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .logo h1 { margin: 0; color: #333; }
        .company-details { text-align: right; }
        .invoice-info { margin-bottom: 30px; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .invoice-table th { background-color: #f9f9f9; }
        .total-section { text-align: right; }
        .total-label { font-weight: bold; margin-right: 20px; }
        .total-amount { font-size: 1.2em; font-weight: bold; color: #2563eb; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-header">
        <div class="logo">
            <h1>Childcare Center</h1>
        </div>
        <div class="company-details">
            <p>123 Childcare Lane</p>
            <p>City, State, Zip</p>
            <p>Phone: (555) 123-4567</p>
        </div>
    </div>

    <div class="invoice-info">
        <h2>INVOICE</h2>
        <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
    </div>

    <div class="billing-to">
        <h3>Bill To:</h3>
        <p><strong>Parent:</strong> {{ $invoice->parent->name ?? 'N/A' }}</p>
        <p><strong>Child:</strong> {{ $invoice->child->first_name ?? 'N/A' }} {{ $invoice->child->last_name ?? '' }}</p>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tuition/Care Services for {{ $invoice->child->first_name ?? 'Child' }}</td>
                <td>${{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <span class="total-label">Total Due:</span>
        <span class="total-amount">${{ number_format($invoice->amount, 2) }}</span>
    </div>

    <div style="margin-top: 50px; text-align: center; color: #666; font-size: 0.9em;">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
