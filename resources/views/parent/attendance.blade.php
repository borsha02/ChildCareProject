<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/attendance.css'])
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
                    <a href="{{ route('parent.attendance') }}" class="nav-item active">
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
                        <span class="badge">3</span>
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
                    <a href="{{ route('parent.invoice') }}" class="nav-item">
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
                    <h1>Attendance Tracking</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
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
                <div class="attendance-container">

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>94%</h3>
                    <p>Attendance Rate</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-details">
                    <h3>23</h3>
                    <p>Days Present</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>2</h3>
                    <p>Days Absent</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>1</h3>
                    <p>Times Late</p>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Calendar Card -->
            <div class="card">
                <div class="card-header">
                    <h2>Monthly Calendar</h2>
                    <div class="month-selector">
                        <button class="month-btn" onclick="previousMonth()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="current-month" id="currentMonth">December 2025</span>
                        <button class="month-btn" onclick="nextMonth()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="calendar" id="calendar">
                    <!-- Calendar Headers -->
                    <div class="calendar-day header">Sun</div>
                    <div class="calendar-day header">Mon</div>
                    <div class="calendar-day header">Tue</div>
                    <div class="calendar-day header">Wed</div>
                    <div class="calendar-day header">Thu</div>
                    <div class="calendar-day header">Fri</div>
                    <div class="calendar-day header">Sat</div>

                    <!-- Sample Calendar Days -->
                    <div class="calendar-day empty"></div>
                    <div class="calendar-day present">
                        <span class="day-number">1</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">2</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">3</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">4</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">5</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">6</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">7</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">8</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">9</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">10</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day absent">
                        <span class="day-number">11</span>
                        <span class="day-status">Absent</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">12</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">13</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">14</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">15</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">16</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">17</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day absent">
                        <span class="day-number">18</span>
                        <span class="day-status">Absent</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">19</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">20</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day present">
                        <span class="day-number">21</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day today present">
                        <span class="day-number">22</span>
                        <span class="day-status">Present</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">23</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">24</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">25</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">26</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">27</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">28</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">29</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">30</span>
                    </div>
                    <div class="calendar-day future">
                        <span class="day-number">31</span>
                    </div>
                </div>

                <!-- Legend -->
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color present"></div>
                        <span>Present</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color absent"></div>
                        <span>Absent</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color future"></div>
                        <span>Future</span>
                    </div>
                </div>
            </div>

            <!-- Attendance Summary Card -->
            <div class="card">
                <div class="card-header">
                    <h2>Quick Summary</h2>
                </div>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; color: white;">
                        <div style="font-size: 48px; font-weight: bold; margin-bottom: 8px;">94%</div>
                        <div style="font-size: 14px; opacity: 0.9;">Overall Attendance Rate</div>
                    </div>

                    <div style="display: grid; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Total Days:</span>
                            <span style="color: #1f2937; font-weight: 600;">25</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Present:</span>
                            <span style="color: #059669; font-weight: 600;">23</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Absent:</span>
                            <span style="color: #dc2626; font-weight: 600;">2</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Late Arrivals:</span>
                            <span style="color: #d97706; font-weight: 600;">1</span>
                        </div>
                    </div>

                    <div style="padding: 16px; background: #fef3c7; border-radius: 10px; border-left: 4px solid #f59e0b;">
                        <div style="font-size: 13px; color: #92400e; font-weight: 600; margin-bottom: 4px;">
                            <i class="fas fa-info-circle"></i> Note
                        </div>
                        <div style="font-size: 12px; color: #78350f;">
                            Excellent attendance! Keep up the good work.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card">
            <div class="card-header">
                <h2>Recent Attendance History</h2>
            </div>

            <!-- Child Filter -->
            <div class="child-filter">
                <button class="filter-btn active">All Children</button>
                <button class="filter-btn">Emma Doe</button>
                <button class="filter-btn">Lucas James</button>
            </div>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Child</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dec 22, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small">EM</div>
                                <span>Emma Doe</span>
                            </div>
                        </td>
                        <td><span class="status-badge present">Present</span></td>
                        <td><span class="time-in">8:30 AM</span></td>
                        <td><span class="time-out">4:00 PM</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Dec 22, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                <span>Lucas James</span>
                            </div>
                        </td>
                        <td><span class="status-badge present">Present</span></td>
                        <td><span class="time-in">8:45 AM</span></td>
                        <td><span class="time-out">3:45 PM</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Dec 21, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small">EM</div>
                                <span>Emma Doe</span>
                            </div>
                        </td>
                        <td><span class="status-badge present">Present</span></td>
                        <td><span class="time-in">8:25 AM</span></td>
                        <td><span class="time-out">4:15 PM</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Dec 21, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                <span>Lucas James</span>
                            </div>
                        </td>
                        <td><span class="status-badge present">Present</span></td>
                        <td><span class="time-in">9:00 AM</span></td>
                        <td><span class="time-out">4:00 PM</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Dec 20, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small">EM</div>
                                <span>Emma Doe</span>
                            </div>
                        </td>
                        <td><span class="status-badge present">Present</span></td>
                        <td><span class="time-in">8:35 AM</span></td>
                        <td><span class="time-out">4:10 PM</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Dec 20, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                <span>Lucas James</span>
                            </div>
                        </td>
                        <td><span class="status-badge late">Late</span></td>
                        <td><span class="time-in">9:30 AM</span></td>
                        <td><span class="time-out">4:00 PM</span></td>
                        <td>Doctor appointment</td>
                    </tr>
                    <tr>
                        <td>Dec 18, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small">EM</div>
                                <span>Emma Doe</span>
                            </div>
                        </td>
                        <td><span class="status-badge absent">Absent</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td>Sick leave</td>
                    </tr>
                    <tr>
                        <td>Dec 11, 2025</td>
                        <td>
                            <div class="child-name">
                                <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                <span>Lucas James</span>
                            </div>
                        </td>
                        <td><span class="status-badge absent">Absent</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td>Family vacation</td>
                    </tr>
                </tbody>
            </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Month navigation
        let currentMonth = new Date();

        function previousMonth() {
            currentMonth.setMonth(currentMonth.getMonth() - 1);
            updateMonthDisplay();
        }

        function nextMonth() {
            currentMonth.setMonth(currentMonth.getMonth() + 1);
            updateMonthDisplay();
        }

        function updateMonthDisplay() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            document.getElementById('currentMonth').textContent =
                monthNames[currentMonth.getMonth()] + ' ' + currentMonth.getFullYear();
        }

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

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
