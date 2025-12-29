<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/analytics.css'])
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
                    <a href="{{ route('admin.analytics') }}" class="nav-item active">
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
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Analytics Dashboard</h1>
                </div>
                <div class="top-bar-actions">
                    <select class="filter-select">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Last 3 Months</option>
                        <option>Last Year</option>
                    </select>
                    <button class="export-btn">
                        <i class="fas fa-download"></i>
                        Export Report
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Key Metrics Row -->
                <div class="metrics-row">
                    <div class="metric-card">
                        <div class="metric-label">Total Revenue</div>
                        <div class="metric-value">${{ number_format($analytics['enrollment']['total'] ?? 0) }}</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            12% from last month
                        </div>
                    </div>
                    <div class="metric-card green">
                        <div class="metric-label">Active Children</div>
                        <div class="metric-value">{{ $analytics['enrollment']['active'] ?? 0 }}</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            8 new this month
                        </div>
                    </div>
                    <div class="metric-card orange">
                        <div class="metric-label">Avg Attendance</div>
                        <div class="metric-value">{{ $analytics['feedback']['positive'] ?? 0 }}%</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            3% improvement
                        </div>
                    </div>
                    <div class="metric-card purple">
                        <div class="metric-label">Parent Satisfaction</div>
                        <div class="metric-value">{{ $analytics['feedback']['positive'] ?? 0 }}%</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            5% increase
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="analytics-grid">
                    <!-- Attendance Chart -->
                    <div class="analytics-card">
                        <div class="card-header">
                            <h3>Weekly Attendance</h3>
                            <div class="card-icon blue">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="bar-chart">
                                @foreach($analytics['attendance']['labels'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $index => $day)
                                <div class="bar-item">
                                    <div class="bar" style="height: {{ ($analytics['attendance']['data'][$index] ?? 0) }}%;">
                                        <span class="bar-value">{{ $analytics['attendance']['data'][$index] ?? 0 }}%</span>
                                    </div>
                                    <div class="bar-label">{{ $day }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Distribution -->
                    <div class="analytics-card">
                        <div class="card-header">
                            <h3>Parent Feedback</h3>
                            <div class="card-icon green">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="pie-chart-container">
                                <div class="pie-chart">
                                    <div class="pie-center">
                                        <h4>{{ $analytics['feedback']['positive'] ?? 85 }}%</h4>
                                        <p>Positive</p>
                                    </div>
                                </div>
                                <div class="pie-legend">
                                    <div class="legend-item">
                                        <div class="legend-color green"></div>
                                        <div class="legend-text">
                                            <h5>Positive</h5>
                                            <p>Very satisfied</p>
                                        </div>
                                        <div class="legend-value">{{ $analytics['feedback']['positive'] ?? 85 }}%</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color yellow"></div>
                                        <div class="legend-text">
                                            <h5>Neutral</h5>
                                            <p>Satisfied</p>
                                        </div>
                                        <div class="legend-value">{{ $analytics['feedback']['neutral'] ?? 10 }}%</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color red"></div>
                                        <div class="legend-text">
                                            <h5>Negative</h5>
                                            <p>Needs improvement</p>
                                        </div>
                                        <div class="legend-value">{{ $analytics['feedback']['negative'] ?? 5 }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <h3>Monthly Revenue Trend</h3>
                        <div class="card-icon purple">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart">
                            @foreach($analytics['payments']['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] as $index => $month)
                            <div class="bar-item">
                                <div class="bar" style="height: {{ (($analytics['payments']['data'][$index] ?? 0) / 200) }}%; background: linear-gradient(180deg, #8b5cf6, #7c3aed);">
                                    <span class="bar-value">${{ number_format($analytics['payments']['data'][$index] ?? 0) }}</span>
                                </div>
                                <div class="bar-label">{{ $month }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Enrollment Statistics -->
                <div class="analytics-card">
                    <div class="card-header">
                        <h3>Enrollment Statistics</h3>
                        <div class="card-icon orange">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Count</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Enrolled</td>
                                <td>{{ $analytics['enrollment']['total'] ?? 120 }}</td>
                                <td>
                                    <span class="trend-badge up">
                                        <i class="fas fa-arrow-up"></i>
                                        8.5%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>New This Month</td>
                                <td>{{ $analytics['enrollment']['new_this_month'] ?? 8 }}</td>
                                <td>
                                    <span class="trend-badge up">
                                        <i class="fas fa-arrow-up"></i>
                                        12%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Active Students</td>
                                <td>{{ $analytics['enrollment']['active'] ?? 115 }}</td>
                                <td>
                                    <span class="trend-badge up">
                                        <i class="fas fa-arrow-up"></i>
                                        5%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Inactive Students</td>
                                <td>{{ $analytics['enrollment']['inactive'] ?? 5 }}</td>
                                <td>
                                    <span class="trend-badge down">
                                        <i class="fas fa-arrow-down"></i>
                                        2%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
