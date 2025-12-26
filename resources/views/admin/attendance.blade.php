<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/attendance.css'])
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
                    <a href="{{ route('admin.attendance') }}" class="nav-item active">
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
                <h1>Attendance Monitoring</h1>
                <div class="top-bar-actions">
                    <input type="date" class="date-picker" value="{{ date('Y-m-d') }}">
                    <button class="export-btn" onclick="exportAttendance()">
                        <i class="fas fa-download"></i>
                        Export Report
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-label">Present Today</div>
                        <div class="stat-value">{{ $stats['present_today'] ?? 85 }}</div>
                        <div class="stat-percentage">Out of 100 children</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-label">Absent Today</div>
                        <div class="stat-value">{{ $stats['absent_today'] ?? 12 }}</div>
                        <div class="stat-percentage">12% absence rate</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-label">Late Arrivals</div>
                        <div class="stat-value">{{ $stats['late_today'] ?? 3 }}</div>
                        <div class="stat-percentage">3% late rate</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-label">Attendance Rate</div>
                        <div class="stat-value">{{ $stats['attendance_rate'] ?? 85 }}%</div>
                        <div class="stat-percentage">Above average</div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Today's Attendance</h3>
                        <div class="filter-tabs">
                            <button class="tab-btn active" onclick="filterAttendance('all')">All</button>
                            <button class="tab-btn" onclick="filterAttendance('present')">Present</button>
                            <button class="tab-btn" onclick="filterAttendance('absent')">Absent</button>
                            <button class="tab-btn" onclick="filterAttendance('late')">Late</button>
                        </div>
                    </div>

                    <div class="search-box">
                        <input type="text" placeholder="Search by child name or class..." id="searchInput">
                        <i class="fas fa-search"></i>
                    </div>

                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Child Name</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Check-In Time</th>
                                <th>Check-Out Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            <!-- Sample Data -->
                            <tr data-status="present">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">EM</div>
                                        <div class="student-details">
                                            <h4>Emma Martinez</h4>
                                            <p>ID: CH001</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Preschool A</td>
                                <td><span class="status-badge present">Present</span></td>
                                <td><span class="time-badge">8:15 AM</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="present">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar" style="background: linear-gradient(135deg, #10b981, #059669);">LJ</div>
                                        <div class="student-details">
                                            <h4>Lucas Johnson</h4>
                                            <p>ID: CH002</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Toddler B</td>
                                <td><span class="status-badge present">Present</span></td>
                                <td><span class="time-badge">7:45 AM</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="late">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);">SW</div>
                                        <div class="student-details">
                                            <h4>Sophia Williams</h4>
                                            <p>ID: CH003</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Kindergarten</td>
                                <td><span class="status-badge late">Late</span></td>
                                <td><span class="time-badge">9:30 AM</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="absent">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar" style="background: linear-gradient(135deg, #ef4444, #dc2626);">OB</div>
                                        <div class="student-details">
                                            <h4>Oliver Brown</h4>
                                            <p>ID: CH004</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Preschool B</td>
                                <td><span class="status-badge absent">Absent</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="present">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">AD</div>
                                        <div class="student-details">
                                            <h4>Ava Davis</h4>
                                            <p>ID: CH005</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Toddler A</td>
                                <td><span class="status-badge present">Present</span></td>
                                <td><span class="time-badge">8:00 AM</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="present">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar" style="background: linear-gradient(135deg, #ec4899, #db2777);">NM</div>
                                        <div class="student-details">
                                            <h4>Noah Miller</h4>
                                            <p>ID: CH006</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Preschool A</td>
                                <td><span class="status-badge present">Present</span></td>
                                <td><span class="time-badge">8:20 AM</span></td>
                                <td><span class="time-badge">-</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">4</button>
                        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Filter attendance by status
        function filterAttendance(status) {
            const rows = document.querySelectorAll('#attendanceTableBody tr');
            const tabs = document.querySelectorAll('.tab-btn');

            // Update active tab
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            // Filter rows
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.dataset.status === status ? '' : 'none';
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Export attendance
        function exportAttendance() {
            alert('Exporting attendance report...\nThis will download a CSV/Excel file with all attendance data.');
            // In real implementation, this would trigger a download
        }

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
