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
                    <a href="{{ route('admin.announcements') }}" class="nav-item">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
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
                    <a href="{{ route('admin.backup') }}" class="nav-item">
                        <i class="fas fa-database"></i>
                        <span>Backup & Restore</span>
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
                    <button class="generate-btn">
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
                        <div class="stat-value">$45,280</div>
                        <div class="stat-description">This month</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Paid Invoices</div>
                        <div class="stat-value">28</div>
                        <div class="stat-description">$38,500 collected</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">12</div>
                        <div class="stat-description">$5,800 outstanding</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-label">Overdue</div>
                        <div class="stat-value">3</div>
                        <div class="stat-description">$980 overdue</div>
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
                            <tr data-status="paid">
                                <td><span class="invoice-number">INV-2025-001</span></td>
                                <td>Sarah Martinez</td>
                                <td>Emma Martinez</td>
                                <td>$1,200</td>
                                <td>Dec 15, 2025</td>
                                <td><span class="status-badge paid">Paid</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="pending">
                                <td><span class="invoice-number">INV-2025-002</span></td>
                                <td>Michael Johnson</td>
                                <td>Lucas Johnson</td>
                                <td>$1,150</td>
                                <td>Dec 20, 2025</td>
                                <td><span class="status-badge pending">Pending</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="paid">
                                <td><span class="invoice-number">INV-2025-003</span></td>
                                <td>Jennifer Williams</td>
                                <td>Sophia Williams</td>
                                <td>$1,300</td>
                                <td>Dec 10, 2025</td>
                                <td><span class="status-badge paid">Paid</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="overdue">
                                <td><span class="invoice-number">INV-2025-004</span></td>
                                <td>David Brown</td>
                                <td>Oliver Brown</td>
                                <td>$1,100</td>
                                <td>Dec 5, 2025</td>
                                <td><span class="status-badge overdue">Overdue</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="pending">
                                <td><span class="invoice-number">INV-2025-005</span></td>
                                <td>Emily Davis</td>
                                <td>Ava Davis</td>
                                <td>$950</td>
                                <td>Dec 25, 2025</td>
                                <td><span class="status-badge pending">Pending</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="paid">
                                <td><span class="invoice-number">INV-2025-006</span></td>
                                <td>Robert Miller</td>
                                <td>Noah Miller</td>
                                <td>$1,250</td>
                                <td>Dec 12, 2025</td>
                                <td><span class="status-badge paid">Paid</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
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
