<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Logs - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/reports.css'])
</head>
<body>
    <div class="dashboard-container">
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
                    <a href="{{ route('admin.invoices') }}" class="nav-item">
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

                    <a href="{{ route('admin.communication') }}" class="nav-item active">
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

        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Communication Logs</h1>
                </div>
                <div class="top-bar-actions">
                    <input type="date" id="communication_date" class="date-picker">
                </div>
            </div>

            <div class="content-area">
                <div class="filter-bar">
                    <select class="filter-select">
                        <option value="">All Types</option>
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                        <option value="notification">Notification</option>
                    </select>
                    <select class="filter-select">
                        <option value="">All Users</option>
                        <option value="parents">Parents</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="reports-grid">
                    <div class="report-card activity">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Email: Monthly Invoice Sent</h3>
                                <div class="report-meta">Today, 9:00 AM</div>
                            </div>
                            <div class="report-icon activity">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">To</span>
                                <span class="report-value">Sarah Martinez</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Subject</span>
                                <span class="report-value">December Invoice</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Status</span>
                                <span class="report-value">Delivered</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Invoice for December 2025 has been sent successfully.
                        </div>
                    </div>

                    <div class="report-card health">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>SMS: Pickup Reminder</h3>
                                <div class="report-meta">Today, 5:30 PM</div>
                            </div>
                            <div class="report-icon health">
                                <i class="fas fa-sms"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">To</span>
                                <span class="report-value">Michael Johnson</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Message</span>
                                <span class="report-value">Pickup reminder for Lucas</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Status</span>
                                <span class="report-value">Sent</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Reminder sent 30 minutes before closing time.
                        </div>
                    </div>



                    <div class="report-card activity">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Email: Welcome Message</h3>
                                <div class="report-meta">Yesterday, 3:15 PM</div>
                            </div>
                            <div class="report-icon activity">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">To</span>
                                <span class="report-value">New Parent - Emily Davis</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Subject</span>
                                <span class="report-value">Welcome to Little Stars</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Status</span>
                                <span class="report-value">Delivered</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Welcome email sent to newly enrolled parent.
                        </div>
                    </div>

                    <div class="report-card health">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>SMS: Absence Notification</h3>
                                <div class="report-meta">Yesterday, 9:30 AM</div>
                            </div>
                            <div class="report-icon health">
                                <i class="fas fa-sms"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">To</span>
                                <span class="report-value">Jennifer Williams</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Message</span>
                                <span class="report-value">Sophia marked absent today</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Status</span>
                                <span class="report-value">Sent</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Automated absence notification sent to parent.
                        </div>
                    </div>

                    <div class="report-card meal">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Notification: Payment Received</h3>
                                <div class="report-meta">Dec 22, 2025</div>
                            </div>
                            <div class="report-icon meal">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">To</span>
                                <span class="report-value">Sarah Martinez</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Title</span>
                                <span class="report-value">Payment Confirmation</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Amount</span>
                                <span class="report-value">$1,200</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Payment confirmation notification sent.
                        </div>
                    </div>
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

        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('communication_date');
            if (dateInput) {
                // Set to today's date in local time
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                dateInput.value = `${year}-${month}-${day}`;
            }
        });
    </script>
</body>
</html>
