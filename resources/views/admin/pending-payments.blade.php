<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approvals - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/invoices.css'])
</head>
<body>
    <div class="dashboard-container">
        @include('admin.partials.sidebar')

        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Payment Approvals</h1>
                </div>
            </div>

            <div class="content-area">
                <div class="stats-row">
                    <div class="stat-card orange">
                        <div class="stat-label">Pending Approvals</div>
                        <div class="stat-value">{{ $pendingCount }}</div>
                        <div class="stat-description">Awaiting review</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-label">Approved Today</div>
                        <div class="stat-value">{{ $approvedTodayCount }}</div>
                        <div class="stat-description">${{ number_format($approvedTodayAmount, 2) }} approved</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-label">Rejected</div>
                        <div class="stat-value">{{ $rejectedWeekCount }}</div>
                        <div class="stat-description">This week</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Processed</div>
                        <div class="stat-value">{{ $totalProcessedMonthCount }}</div>
                        <div class="stat-description">This month</div>
                    </div>
                </div>

                <div class="invoices-card">
                    <div class="card-header">
                        <h3>Pending Payment Approvals</h3>
                    </div>

                    <table class="invoices-table">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Parent Name</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingPayments as $payment)
                            <tr>
                                <td><span class="invoice-number">PAY-{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                                <td>{{ $payment->invoice->parent->name ?? 'Unknown' }}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->card_type ?? 'Online' }}</td>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- View Receipt -->
                                        <a href="{{ route('payment.receipt.download', $payment->transaction_id) }}" class="action-icon view" title="View Receipt" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Approve Form -->
                                        @if($payment->status !== 'Approved')
                                        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-icon download" title="Approve" onclick="return confirm('Approve this payment?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="action-icon" style="opacity: 0.5; cursor: default;" title="Already Approved">
                                            <i class="fas fa-check-double" style="color: green;"></i>
                                        </button>
                                        @endif

                                        <!-- Reject Form -->
                                        @if($payment->status !== 'Rejected')
                                        <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-icon edit" title="Reject" onclick="return confirm('Reject this payment?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 4px;">Rejected</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">No payments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
