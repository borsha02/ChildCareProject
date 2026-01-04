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
                    <h2>Childcare</h2>
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
                    <div class="search-box">
                        <input type="text" placeholder="Search invoices...">
                        <i class="fas fa-search"></i>
                    </div>
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
                                <h3>$450.00</h3>
                                <p>Pending Payment</p>
                            </div>
                        </div>
                        <div class="billing-card">
                            <div class="billing-icon paid">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="billing-details">
                                <h3>$2,850.00</h3>
                                <p>Paid This Year</p>
                            </div>
                        </div>
                        <div class="billing-card">
                            <div class="billing-icon upcoming">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="billing-details">
                                <h3>Jan 1, 2026</h3>
                                <p>Next Payment Due</p>
                            </div>
                        </div>
                        <div class="billing-card">
                            <div class="billing-icon total">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="billing-details">
                                <h3>12</h3>
                                <p>Total Invoices</p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Invoice -->
                    <div class="card current-invoice">
                        <div class="card-header">
                            <h2><i class="fas fa-file-invoice"></i> Current Invoice</h2>
                            <div class="invoice-actions">
                                <button class="action-btn download">
                                    <i class="fas fa-download"></i> Download PDF
                                </button>
                                <button class="action-btn pay">
                                    <i class="fas fa-credit-card"></i> Pay Now
                                </button>
                            </div>
                        </div>
                        <div class="invoice-content">
                            <div class="invoice-header-info">
                                <div class="invoice-number">
                                    <h3>Invoice #INV-2025-12-001</h3>
                                    <p>Issue Date: December 1, 2025</p>
                                    <p>Due Date: December 31, 2025</p>
                                </div>
                                <div class="invoice-status-badge pending">
                                    <i class="fas fa-exclamation-circle"></i> Payment Pending
                                </div>
                            </div>

                            <div class="invoice-details">
                                <div class="billing-info">
                                    <h4>Bill To:</h4>
                                    <p><strong>John Doe</strong></p>
                                    <p>123 Main Street</p>
                                    <p>New York, NY 10001</p>
                                    <p>Phone: +1 (555) 123-4567</p>
                                </div>
                                <div class="facility-info">
                                    <h4>From:</h4>
                                    <p><strong>Sunshine Childcare Center</strong></p>
                                    <p>456 Oak Avenue</p>
                                    <p>New York, NY 10002</p>
                                    <p>Phone: +1 (555) 987-6543</p>
                                </div>
                            </div>

                            <table class="invoice-table">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Child</th>
                                        <th>Period</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Monthly Tuition</td>
                                        <td>Emma Doe</td>
                                        <td>Dec 2025</td>
                                        <td>1 month</td>
                                        <td>$200.00</td>
                                        <td>$200.00</td>
                                    </tr>
                                    <tr>
                                        <td>Monthly Tuition</td>
                                        <td>Lucas James</td>
                                        <td>Dec 2025</td>
                                        <td>1 month</td>
                                        <td>$200.00</td>
                                        <td>$200.00</td>
                                    </tr>
                                    <tr>
                                        <td>Meal Plan</td>
                                        <td>Emma Doe</td>
                                        <td>Dec 2025</td>
                                        <td>1 month</td>
                                        <td>$50.00</td>
                                        <td>$50.00</td>
                                    </tr>
                                    <tr>
                                        <td>Activity Fee</td>
                                        <td>Both Children</td>
                                        <td>Dec 2025</td>
                                        <td>1 month</td>
                                        <td>$30.00</td>
                                        <td>$30.00</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="invoice-summary">
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span>$480.00</span>
                                </div>
                                <div class="summary-row">
                                    <span>Discount (Sibling 10%):</span>
                                    <span class="discount">-$30.00</span>
                                </div>
                                <div class="summary-row total">
                                    <span>Total Due:</span>
                                    <span>$450.00</span>
                                </div>
                            </div>

                            <div class="payment-methods">
                                <h4>Accepted Payment Methods:</h4>
                                <div class="payment-icons">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                    <i class="fab fa-cc-amex"></i>
                                    <i class="fab fa-paypal"></i>
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice History -->
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-history"></i> Invoice History</h2>
                            <div class="filter-group">
                                <select class="filter-select">
                                    <option value="all">All Invoices</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>
                        </div>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>INV-2025-12-001</td>
                                    <td>Dec 1, 2025</td>
                                    <td>December Tuition & Fees</td>
                                    <td>$450.00</td>
                                    <td><span class="status-badge pending">Pending</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="action-icon-btn pay-btn" title="Pay">
                                            <i class="fas fa-credit-card"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-2025-11-001</td>
                                    <td>Nov 1, 2025</td>
                                    <td>November Tuition & Fees</td>
                                    <td>$450.00</td>
                                    <td><span class="status-badge paid">Paid</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-2025-10-001</td>
                                    <td>Oct 1, 2025</td>
                                    <td>October Tuition & Fees</td>
                                    <td>$450.00</td>
                                    <td><span class="status-badge paid">Paid</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-2025-09-001</td>
                                    <td>Sep 1, 2025</td>
                                    <td>September Tuition & Fees</td>
                                    <td>$450.00</td>
                                    <td><span class="status-badge paid">Paid</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
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
                            <p>Payments are due by the last day of each month. Late payments may incur a $25 late fee. For payment assistance or questions, please contact our billing department at billing@sunshinechildcare.com or call (555) 987-6543.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

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
