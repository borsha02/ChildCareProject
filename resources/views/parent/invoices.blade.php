<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Invoices - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/invoice.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Little Stars Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <h4>{{Auth::user()->name}}</h4>
                        <p>Parent Account</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('parent.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('parent.child-profile') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Profile</span>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('parent.reports') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('parent.messages') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        @php
                            $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <a href="{{ route('parent.health') }}" class="nav-item">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="nav-item active">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="nav-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Assigned Caregivers</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="{{ route('parent.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('parent.help') }}" class="nav-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                    <a href="{{ route('logout') }}" class="nav-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('parent.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Billing & Invoices</h1>
                </div>
                <div class="top-bar-actions">
                   <!-- <div class="search-box">
                        <input type="text" placeholder="Search invoices...">
                        <i class="fas fa-search"></i>
                    </div> -->
                    <a href="{{ route('parent.notifications') }}" class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
                    </a>
                    <button class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            <div class="content-area">
                <div class="invoice-container">
                    <!-- Billing Summary Cards -->
                    <div class="billing-stats">
                        <div class="billing-card">
                            <div class="billing-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="billing-details">
                                <h3>{{ number_format($pendingPayment, 2) }}</h3>
                                <p>Pending Payment</p>
                            </div>
                        </div>
                        <div class="billing-card">
                            <div class="billing-icon paid">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="billing-details">
                                <h3>{{ number_format($paidThisYear, 2) }}</h3>
                                <p>Paid This Year</p>
                            </div>
                        </div>
                        <div class="billing-card">
                            <div class="billing-icon upcoming">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="billing-details">
                                <h3>{{ $nextPaymentDue }}</h3>
                                <p>Next Payment Due</p>
                            </div>
                        </div>
                        <div class="billing-card">
                            <div class="billing-icon total">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="billing-details">
                                <h3>{{ $totalInvoices }}</h3>
                                <p>Total Invoices</p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Invoice -->
                    @if($latestInvoice)
                    <div class="card current-invoice">
                        <div class="card-header">
                            <h2><i class="fas fa-file-invoice"></i> Latest Invoice</h2>
                            <div class="invoice-actions">
                                <a href="{{ route('parent.invoices.download', $latestInvoice->id) }}" class="action-btn download" target="_blank">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                                @if($latestInvoice->status == 'pending')
                                <form action="{{ route('payment.pay', $latestInvoice->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn pay">
                                        <i class="fas fa-credit-card"></i> Pay Now
                                    </button>
                                </form>
                                @endif
                                
                                @if($latestInvoice->status == 'paid' && $latestInvoice->successfulPayment)
                                <a href="{{ route('payment.receipt.download', $latestInvoice->successfulPayment->transaction_id) }}" class="action-btn download" target="_blank">
                                    <i class="fas fa-receipt"></i> Receipt
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="invoice-content">
                            <div class="invoice-header-info">
                                <div class="invoice-number">
                                    <h3>Invoice #{{ $latestInvoice->invoice_number }}</h3>
                                    <p>Issue Date: {{ $latestInvoice->created_at->format('M d, Y') }}</p>
                                    <p>Due Date: {{ \Carbon\Carbon::parse($latestInvoice->due_date)->format('M d, Y') }}</p>
                                </div>
                                <div class="invoice-status-badge {{ $latestInvoice->status }}">
                                    <i class="fas fa-exclamation-circle"></i> Payment {{ ucfirst($latestInvoice->status) }}
                                </div>
                            </div>

                            <div class="invoice-details">
                                <div class="billing-info">
                                    <h4>Bill To:</h4>
                                    <p><strong>{{ Auth::user()->name }}</strong></p>
                                    <p>{{ Auth::user()->email }}</p>
                                    <p>{{ Auth::user()->phone }}</p>
                                </div>
                                <div class="facility-info">
                                    <h4>From:</h4>
                                    <p><strong>{{ $settings['system_name'] ?? 'Childcare Center' }}</strong></p>
                                    <p>{{ $settings['address'] ?? 'Address Not Configured' }}</p>
                                    @if(!empty($settings['contact_phone']))
                                    <p>{{ $settings['contact_phone'] }}</p>
                                    @endif
                                </div>
                            </div>

                            <table class="invoice-table">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Child</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(($latestInvoice->discount ?? 0) > 0)
                                        <tr>
                                            <td>Tuition/Care Services</td>
                                            <td>{{ $latestInvoice->child->first_name }} {{ $latestInvoice->child->last_name }}</td>
                                            <td>{{ number_format($latestInvoice->amount + $latestInvoice->discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="color: #666; font-style: italic;">Discount Applied</td>
                                            <td style="color: #666;">- {{ number_format($latestInvoice->discount, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>Tuition/Care Services</td>
                                            <td>{{ $latestInvoice->child->first_name }} {{ $latestInvoice->child->last_name }}</td>
                                            <td>{{ number_format($latestInvoice->amount, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div class="invoice-summary">
                                <div class="summary-row total">
                                    <span>Total Due:</span>
                                    <span>{{ number_format($latestInvoice->amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Invoice History -->
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-history"></i> Invoice History</h2>
                        </div>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Child</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td>{{ $invoice->child->first_name }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }}</td>
                                    <td><span class="status-badge {{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="View" onclick="viewInvoice({{ $invoice->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('parent.invoices.download', $invoice->id) }}" class="action-icon-btn" title="Download Invoice" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if($invoice->status == 'pending')
                                        <form action="{{ route('payment.pay', $invoice->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-icon-btn pay-btn" title="Pay Now">
                                                <i class="fas fa-credit-card"></i>
                                            </button>
                                        </form>
                                        @elseif($invoice->status == 'paid' && $invoice->successfulPayment)
                                        <a href="{{ route('payment.receipt.download', $invoice->successfulPayment->transaction_id) }}" class="action-icon-btn" title="Download Receipt" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;">No invoices found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Information -->
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="info-content">
                            <h4>Payment Information</h4>
                            <p>Payments are due by the last day of each month. Late payments may incur a 250 Taka late fee. For payment assistance or questions, please contact our billing department at {{ $settings['contact_email'] ?? 'info.littlestars.childcarecenter@gmail.com' }} or call {{ $settings['contact_phone'] ?? '(555) 987-6543' }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Invoice Modal -->
    <div id="invoiceModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeInvoiceModal()">&times;</span>
            <h2 id="modalTitle">Invoice Details</h2>
            <div class="form-group">
                <label>Invoice Number</label>
                <input type="text" id="view_invoice_number" readonly>
            </div>
            <div class="form-group">
                <label>Child</label>
                <input type="text" id="view_child_name" readonly>
            </div>
                <label>Amount</label>
                <input type="text" id="view_amount" readonly>
            </div>
            <div class="form-group" id="view_discount_group" style="display:none;">
                <label>Discount Applied</label>
                <input type="text" id="view_discount" readonly style="color: #d97706;">
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="text" id="view_due_date" readonly>
            </div>
             <div class="form-group">
                <label>Status</label>
                <input type="text" id="view_status" readonly>
            </div>
             <div class="form-group">
                <label>Transaction ID</label>
                <input type="text" id="view_transaction_id" readonly placeholder="N/A">
            </div>
            <div class="form-actions">
                <button class="cancel-btn" onclick="closeInvoiceModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Store invoices in a global variable to avoid multiple parsing and quoting issues
        const invoicesData = @json($invoices);

        function viewInvoice(id) {
            // Find the invoice by ID from the global data
            const invoice = invoicesData.find(inv => inv.id === id);
            
            if (!invoice) {
                console.error('Invoice not found:', id);
                return;
            }

            document.getElementById('invoiceModal').style.display = 'block';
            
            document.getElementById('view_invoice_number').value = invoice.invoice_number;
            document.getElementById('view_child_name').value = (invoice.child ? invoice.child.first_name + ' ' + (invoice.child.last_name || '') : 'N/A');
            document.getElementById('view_amount').value = parseFloat(invoice.amount).toFixed(2);
            
            // Discount Logic
            const discountGroup = document.getElementById('view_discount_group');
            if (invoice.discount && parseFloat(invoice.discount) > 0) {
                discountGroup.style.display = 'block';
                document.getElementById('view_discount').value = '- ' + parseFloat(invoice.discount).toFixed(2);
            } else {
                discountGroup.style.display = 'none';
            }

            document.getElementById('view_due_date').value = new Date(invoice.due_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            
            // Status
            const statusField = document.getElementById('view_status');
            statusField.value = invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1);
            
            // Transaction ID - Check both camelCase (relation name) and snake_case (potential serialization)
            const payment = invoice.successfulPayment || invoice.successful_payment;
            if (payment) {
                document.getElementById('view_transaction_id').value = payment.transaction_id;
            } else {
                document.getElementById('view_transaction_id').value = 'N/A';
            }
        }

        function closeInvoiceModal() {
            document.getElementById('invoiceModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('invoiceModal');
            if (event.target == modal) {
                closeInvoiceModal();
            }
        }
    </script>

    <script>
        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
