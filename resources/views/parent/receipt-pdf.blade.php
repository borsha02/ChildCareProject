<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #ddd; padding-bottom: 20px; margin-bottom: 30px; }
        .logo h1 { margin: 0; color: #4F46E5; }
        .receipt-title { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .receipt-info { margin-bottom: 30px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .label { font-weight: bold; color: #666; }
        .amount-box { background: #f3f4f6; padding: 20px; text-align: center; border-radius: 8px; margin: 20px 0; }
        .amount { font-size: 32px; font-weight: bold; color: #10B981; }
        .status { color: #10B981; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1>Little Stars ChildCare Center</h1>
        </div>
        <p>123 Childcare Lane, City, State, Zip</p>
    </div>

    <div class="header">
        <div class="receipt-title">PAYMENT RECEIPT</div>
        <div>Date: {{ $payment->created_at->format('M d, Y h:i A') }}</div>
    </div>

    <div class="receipt-info">
        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
        <p><strong>Invoice #:</strong> {{ $payment->invoice->invoice_number }}</p>
        <p><strong>Parent Name:</strong> {{ $payment->invoice->parent->name }}</p>
        <p><strong>Child Name:</strong> {{ $payment->invoice->child->first_name }} {{ $payment->invoice->child->last_name }}</p>
    </div>

    <div class="amount-box">
        <div class="label">Amount Paid</div>
        <div class="amount">BDT {{ number_format($payment->amount, 2) }}</div>
        <div class="status">PAID via SSLCommerz</div>
    </div>

    <div class="payment-details">
        <p><strong>Card Type:</strong> {{ $payment->card_type ?? 'N/A' }}</p>
        <p><strong>Validation ID:</strong> {{ $payment->val_id }}</p>
    </div>

    <div class="footer">
        <p>Thank you for your payment!</p>
        <p>This is an electronically generated receipt.</p>
    </div>
</body>
</html>
