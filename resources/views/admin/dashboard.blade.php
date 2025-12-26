<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css'])
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-item active">
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
                        <span class="badge">{{ $stats['pending_approvals'] ?? 0 }}</span>
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
                <h1>Dashboard Overview</h1>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <button class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-child"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_children'] ?? 0 }}</h3>
                            <p>Enrolled Children</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_staff'] ?? 0 }}</h3>
                            <p>Staff Members</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['active_today'] ?? 0 }}</h3>
                            <p>Present Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <h3>${{ number_format($stats['pending_payments'] ?? 0) }}</h3>
                            <p>Pending Payments</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon teal">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-details">
                            <h3>${{ number_format($stats['total_revenue'] ?? 0) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Recent Activities -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Recent Activities</h3>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon user">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>New Parent Registered</h4>
                                    <p>Sarah Johnson created an account</p>
                                </div>
                                <div class="activity-time">10 min ago</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon payment">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Payment Received</h4>
                                    <p>Invoice #1234 paid by John Doe</p>
                                </div>
                                <div class="activity-time">1 hour ago</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon user">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Staff Member Added</h4>
                                    <p>Emily Brown joined as caregiver</p>
                                </div>
                                <div class="activity-time">2 hours ago</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon alert">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Payment Overdue</h4>
                                    <p>Invoice #1230 is 5 days overdue</p>
                                </div>
                                <div class="activity-time">3 hours ago</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Pending Actions</h3>
                            <a href="{{ route('admin.payments.pending') }}" class="view-all">View All</a>
                        </div>
                        <div class="pending-list">
                            <div class="pending-item">
                                <div class="pending-info">
                                    <h4>Payment Approvals</h4>
                                    <p>Requires review</p>
                                </div>
                                <span class="pending-badge">{{ $stats['pending_approvals'] ?? 0 }}</span>
                            </div>
                            <div class="pending-item">
                                <div class="pending-info">
                                    <h4>Staff Applications</h4>
                                    <p>New applications</p>
                                </div>
                                <span class="pending-badge">3</span>
                            </div>
                            <div class="pending-item">
                                <div class="pending-info">
                                    <h4>Overdue Invoices</h4>
                                    <p>Needs follow-up</p>
                                </div>
                                <span class="pending-badge">7</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="content-grid">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="quick-actions">
                            <a href="{{ route('admin.users') }}" class="action-btn">
                                <i class="fas fa-user-plus"></i>
                                <p>Add New User</p>
                            </a>
                            <a href="{{ route('admin.children') }}" class="action-btn">
                                <i class="fas fa-child"></i>
                                <p>Register Child</p>
                            </a>
                            <a href="{{ route('admin.invoices') }}" class="action-btn">
                                <i class="fas fa-file-invoice"></i>
                                <p>Generate Invoice</p>
                            </a>
                            <a href="{{ route('admin.announcements') }}" class="action-btn">
                                <i class="fas fa-bullhorn"></i>
                                <p>Send Announcement</p>
                            </a>
                            <a href="{{ route('admin.reports') }}" class="action-btn">
                                <i class="fas fa-download"></i>
                                <p>Export Reports</p>
                            </a>
                            <a href="{{ route('admin.backup') }}" class="action-btn">
                                <i class="fas fa-database"></i>
                                <p>Backup System</p>
                            </a>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="card">
                        <div class="card-header">
                            <h3>System Status</h3>
                        </div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon payment">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>System Online</h4>
                                    <p>All services operational</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon user">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Last Backup</h4>
                                    <p>Today at 2:00 AM</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon payment">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Server Status</h4>
                                    <p>Healthy - 99.9% uptime</p>
                                </div>
                            </div>
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
