<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css', 'resources/css/admin/sidebar.css'])
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('admin.partials.sidebar', ['stats' => $stats ?? [], 'pendingJobAppsCount' => $pendingJobAppsCount ?? 0])

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
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <a href="{{ route('admin.users') }}" class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                            <p>Total Users</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.children') }}" class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-child"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_children'] ?? 0 }}</h3>
                            <p>Enrolled Children</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.staff') }}" class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_staff'] ?? 0 }}</h3>
                            <p>Staff Members</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.attendance') }}" class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['active_today'] ?? 0 }}</h3>
                            <p>Present Today</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.payments.pending') }}" class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <h3>${{ number_format($stats['pending_payments'] ?? 0) }}</h3>
                            <p>Pending Payments</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="stat-card">
                        <div class="stat-icon teal">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-details">
                            <h3>${{ number_format($stats['total_revenue'] ?? 0) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                    </a>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Recent Activities -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Recent Activities</h3>
                            <a href="{{ route('admin.activities') }}" class="view-all">View All</a>
                        </div>
                        <div class="activity-list">
                            @forelse($recentActivities as $activity)
                            <a href="{{ $activity['link'] }}" class="activity-item" style="text-decoration: none; color: inherit; display: flex;">
                                <div class="activity-icon {{ $activity['color'] == 'success' ? 'payment' : ($activity['color'] == 'orange' ? 'alert' : 'user') }}">
                                    <i class="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>{{ $activity['title'] }}</h4>
                                    <p>{{ $activity['description'] }}</p>
                                </div>
                                <div class="activity-time">{{ $activity['time']->diffForHumans() }}</div>
                            </a>
                            @empty
                            <div class="empty-state" style="text-align: center; padding: 20px; color: #6b7280;">
                                <p>No recent activities found.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pending Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Pending Actions</h3>
                            <a href="{{ route('admin.pending') }}" class="view-all">View All</a>
                        </div>
                        <div class="pending-list">
                            <a href="{{ route('admin.children') }}" class="pending-item">
                                <div class="pending-info">
                                    <h4>Child Registrations</h4>
                                    <p>New pupil requests</p>
                                </div>
                                <span class="pending-badge">{{ $stats['pending_registrations'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.job-applications') }}" class="pending-item">
                                <div class="pending-info">
                                    <h4>Staff Applications</h4>
                                    <p>New job applicants</p>
                                </div>
                                <span class="pending-badge">{{ $stats['pending_applications'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.payments.pending') }}" class="pending-item">
                                <div class="pending-info">
                                    <h4>Payment Approvals</h4>
                                    <p>Requires review</p>
                                </div>
                                <span class="pending-badge">{{ $stats['pending_payments'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.staff') }}" class="pending-item">
                                <div class="pending-info">
                                    <h4>Leave Requests</h4>
                                    <p>Staff time off</p>
                                </div>
                                <span class="pending-badge">{{ $stats['pending_leave_requests'] ?? 0 }}</span>
                            </a>
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
                            {{-- <a href="{{ route('admin.reports') }}" class="action-btn">
                                <i class="fas fa-download"></i>
                                <p>Export Reports</p>
                            </a>
                            <a href="{{ route('admin.backup') }}" class="action-btn">
                                <i class="fas fa-database"></i>
                                <p>Backup System</p>
                            </a> --}}
                        </div>
                    </div>

                    <!-- System Status -->
                    {{-- <div class="card">
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
                    </div> --}}
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
