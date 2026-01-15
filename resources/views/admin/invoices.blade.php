<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Invoices - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/invoices.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <h2>Admin Panel</h2>
                </div>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div class="user-details">
                        <h4>Administrator</h4>
                        <p>System Admin</p>
                    </div>
                </div>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="{{ route('admin.users') }}" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="{{ route('admin.children') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Records</span>
                    </a>
                    <a href="{{ route('admin.staff') }}" class="nav-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Staff Management</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Operations</div>
                    <a href="{{ route('admin.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('admin.invoices') }}" class="nav-item active">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('admin.payments.pending') }}" class="nav-item">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Approvals</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>

                    <a href="{{ route('admin.communication') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Communication Logs</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>

                    <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
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
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Billing & Invoices</h1>
                </div>
                <div class="top-bar-actions">
                    <button class="generate-btn" onclick="openInvoiceModal()">
                        <i class="fas fa-plus"></i>
                        Generate Invoice
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Revenue Statistics -->
                <div class="stats-row">
                    <div class="stat-card green">
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-value">{{ number_format($stats['total_revenue']) }}</div>
                        <div class="stat-description">Paid Invoices</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Paid Invoices</div>
                        <div class="stat-value">{{ $stats['paid_invoices'] }}</div>
                        <div class="stat-description">{{ number_format($stats['collected_revenue']) }} collected</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ $stats['pending_invoices'] }}</div>
                        <div class="stat-description">{{ number_format($stats['outstanding_amount']) }} outstanding</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-label">Overdue</div>
                        <div class="stat-value">{{ $stats['overdue_invoices'] }}</div>
                        <div class="stat-description">{{ number_format($stats['overdue_amount']) }} overdue</div>
                    </div>
                </div>

                <!-- Invoices Table -->
                <div class="invoices-card">
                    <div class="card-header">
                        <h3>All Invoices</h3>
                        <div class="filter-tabs">
                            <button class="tab-btn active" onclick="filterByStatus('all')">All</button>
                            <button class="tab-btn" onclick="filterByStatus('paid')">Paid</button>
                            <button class="tab-btn" onclick="filterByStatus('pending')">Pending</button>
                            <button class="tab-btn" onclick="filterByStatus('overdue')">Overdue</button>
                        </div>
                    </div>

                    <table class="invoices-table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Parent Name</th>
                                <th>Child</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="invoicesTableBody">
                            @forelse($invoices as $invoice)
                            <tr data-status="{{ $invoice->status }}">
                                <td><span class="invoice-number">{{ $invoice->invoice_number }}</span></td>
                                <td>{{ $invoice->parent->name ?? 'N/A' }}</td>
                                <td>{{ $invoice->child->first_name ?? 'N/A' }} {{ $invoice->child->last_name ?? '' }}</td>
                                <td>{{ number_format($invoice->amount, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $invoice->status }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View" onclick='viewInvoice(@json($invoice))'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit" onclick='editInvoice(@json($invoice))'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="action-icon download" title="Download" target="_blank" style="text-decoration: none;">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">No invoices found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <!-- Generate Invoice Modal -->
    <div id="invoiceModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeInvoiceModal()">&times;</span>
            <h2 id="modalTitle">Generate New Invoice</h2>
            <form id="invoiceForm" action="{{ route('admin.invoices.generate') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="form-group">
                    <label for="child_id">Child & Parent</label>
                    <select name="child_id" id="child_id" required>
                        <option value="">Select Child</option>
                        @foreach($children as $child)
                        <option value="{{ $child->id }}" 
                                data-package="{{ $child->package }}"
                                data-enrollment="{{ \Carbon\Carbon::parse($child->enrollment_date)->format('M d, Y') }}"
                                data-is-sibling="{{ $child->is_sibling ? 'true' : 'false' }}">
                            {{ $child->first_name }} {{ $child->last_name }} (Parent: {{ $child->parent->name ?? 'N/A' }})
                        </option>
                        @endforeach
                    </select>
                    <small id="enrollment-info" style="display:none; color: #6b7280; margin-top: 5px; font-size: 12px;"></small>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" required placeholder="0.00">
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="closeInvoiceModal()">Cancel</button>
                    <button type="submit" class="save-btn" id="submitBtn">Generate Invoice</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pass fees from controller
        const feeSettings = @json($fees ?? ['weekly' => 0, 'monthly' => 0, 'sibling_discount' => 0]);

        // Auto-fill amount based on package and show enrollment date
        document.getElementById('child_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const packageType = selectedOption.getAttribute('data-package');
            const enrollmentDate = selectedOption.getAttribute('data-enrollment');
            const isSibling = selectedOption.getAttribute('data-is-sibling') === 'true';
            
            const amountInput = document.getElementById('amount');
            const enrollmentInfo = document.getElementById('enrollment-info');
            
            // Amount logic - Dynamic from Settings with Sibling Discount
            if (packageType === 'weekly') {
                amountInput.value = feeSettings.weekly;
            } else if (packageType === 'monthly') {
                let amount = parseFloat(feeSettings.monthly);
                
                // Apply Sibling Discount only for Monthly package
                if (isSibling && feeSettings.sibling_discount > 0) {
                    const discount = (amount * feeSettings.sibling_discount) / 100;
                    amount = amount - discount;
                }
                
                amountInput.value = amount.toFixed(2);
            } else {
                amountInput.value = '';
            }

            // Enrollment Date logic
            if (enrollmentDate) {
                enrollmentInfo.style.display = 'block';
                enrollmentInfo.textContent = `Assigned/Enrollment Date: ${enrollmentDate}`;
            } else {
                enrollmentInfo.style.display = 'none';
            }
        });

        function openInvoiceModal() {
            document.getElementById('invoiceModal').style.display = 'block';
            document.getElementById('modalTitle').innerText = 'Generate New Invoice';
            document.getElementById('invoiceForm').action = "{{ route('admin.invoices.generate') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('submitBtn').innerText = 'Generate Invoice';
            document.getElementById('submitBtn').style.display = 'block';
            
            // Enable fields in case they were disabled by view
            document.getElementById('child_id').disabled = false;
            document.getElementById('amount').disabled = false;
            document.getElementById('due_date').disabled = false;
            document.getElementById('status').disabled = false;
        }

        function editInvoice(invoice) {
            openInvoiceModal();
            document.getElementById('modalTitle').innerText = 'Edit Invoice';
            document.getElementById('invoiceForm').action = `/admin/invoices/${invoice.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('submitBtn').innerText = 'Update Invoice';

            // Populate fields
            document.getElementById('child_id').value = invoice.child_id;
            document.getElementById('amount').value = invoice.amount;
            if (invoice.due_date) {
                document.getElementById('due_date').value = invoice.due_date.substring(0, 10);
            } else {
                 document.getElementById('due_date').value = '';
            }
            document.getElementById('status').value = invoice.status;
            
            // Trigger change event to show enrollment info if logic exists
             const event = new Event('change');
             document.getElementById('child_id').dispatchEvent(event);
        }

        function viewInvoice(invoice) {
            editInvoice(invoice); // Reuse population logic
            document.getElementById('modalTitle').innerText = 'View Invoice Details';
            document.getElementById('submitBtn').style.display = 'none'; // Hide save button

            // Disable fields
            document.getElementById('child_id').disabled = true;
            document.getElementById('amount').disabled = true;
            document.getElementById('due_date').disabled = true;
            document.getElementById('status').disabled = true;
        }

        function closeInvoiceModal() {
            document.getElementById('invoiceModal').style.display = 'none';
            document.querySelector('#invoiceModal form').reset();
            document.getElementById('child_id').disabled = false; // Reset state
             // Clear hidden method field
            document.getElementById('methodField').innerHTML = '';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('invoiceModal');
            if (event.target == modal) {
                closeInvoiceModal();
            }
        }

        function filterByStatus(status) {
            const rows = document.querySelectorAll('#invoicesTableBody tr');
            const tabs = document.querySelectorAll('.tab-btn');

            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            rows.forEach(row => {
                row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
            });
        }

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
