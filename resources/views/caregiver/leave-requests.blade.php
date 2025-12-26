<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests - Caregiver Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/caregiver/dashboard.css'])
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
                    <div class="user-avatar">SC</div>
                    <div class="user-details">
                        <h4>Sarah Connor</h4>
                        <p>Caregiver</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('caregiver.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('caregiver.assigned') }}" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Assigned Children</span>
                    </a>
                    <a href="{{ route('caregiver.schedule') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>My Schedule</span>
                    </a>
                    <a href="{{ route('caregiver.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Activities</div>
                    <a href="{{ route('caregiver.reports') }}" class="nav-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('caregiver.health') }}" class="nav-item">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('caregiver.events') }}" class="nav-item">
                        <i class="fas fa-calendar-days"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('caregiver.messages') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <span class="badge">4</span>
                    </a>
                    <a href="{{ route('caregiver.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Personal</div>
                    <a href="{{ route('caregiver.leave') }}" class="nav-item active">
                        <i class="fas fa-calendar-times"></i>
                        <span>Leave Requests</span>
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
                <h1>Leave Requests</h1>
                <div class="top-bar-actions">
                    <button style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        <i class="fas fa-plus"></i> New Leave Request
                    </button>
                    <a href="{{ route('caregiver.notifications') }}" class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </a>
                    <a href="{{ route('caregiver.messages') }}" class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-details">
                            <h3>15</h3>
                            <p>Available Days</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>5</h3>
                            <p>Used Days</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-details">
                            <h3>2</h3>
                            <p>Pending Requests</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-details">
                            <h3>3</h3>
                            <p>Upcoming Leave</p>
                        </div>
                    </div>
                </div>

                <!-- Leave Request Form -->
                <div class="card" style="margin-bottom: 30px;">
                    <div class="card-header">
                        <h3>Submit New Leave Request</h3>
                    </div>
                    <form style="display: grid; gap: 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Leave Type</label>
                                <select style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                    <option>Select leave type</option>
                                    <option>Vacation</option>
                                    <option>Sick Leave</option>
                                    <option>Personal</option>
                                    <option>Emergency</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Duration</label>
                                <select style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                    <option>Full Day</option>
                                    <option>Half Day (Morning)</option>
                                    <option>Half Day (Afternoon)</option>
                                </select>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Start Date</label>
                                <input type="date" style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">End Date</label>
                                <input type="date" style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Reason</label>
                            <textarea rows="4" placeholder="Please provide a reason for your leave request..." style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                        </div>
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button" style="padding: 12px 24px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                                Cancel
                            </button>
                            <button type="submit" style="padding: 12px 24px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Leave History -->
                <div class="card">
                    <div class="card-header">
                        <h3>Leave Request History</h3>
                        <select style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            <option>All Requests</option>
                            <option>Pending</option>
                            <option>Approved</option>
                            <option>Rejected</option>
                        </select>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Type</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Start Date</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">End Date</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Days</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Reason</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px; color: #4b5563;">Vacation</td>
                                    <td style="padding: 12px; color: #4b5563;">Jan 15, 2026</td>
                                    <td style="padding: 12px; color: #4b5563;">Jan 19, 2026</td>
                                    <td style="padding: 12px; color: #4b5563;">5</td>
                                    <td style="padding: 12px; color: #4b5563;">Family vacation</td>
                                    <td style="padding: 12px;">
                                        <span style="padding: 6px 12px; background: #fef3c7; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">Pending</span>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px; color: #4b5563;">Personal</td>
                                    <td style="padding: 12px; color: #4b5563;">Dec 28, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">Dec 28, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">1</td>
                                    <td style="padding: 12px; color: #4b5563;">Personal appointment</td>
                                    <td style="padding: 12px;">
                                        <span style="padding: 6px 12px; background: #fef3c7; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">Pending</span>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px; color: #4b5563;">Sick Leave</td>
                                    <td style="padding: 12px; color: #4b5563;">Dec 10, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">Dec 11, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">2</td>
                                    <td style="padding: 12px; color: #4b5563;">Flu symptoms</td>
                                    <td style="padding: 12px;">
                                        <span style="padding: 6px 12px; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Approved</span>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px; color: #4b5563;">Vacation</td>
                                    <td style="padding: 12px; color: #4b5563;">Nov 20, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">Nov 22, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">3</td>
                                    <td style="padding: 12px; color: #4b5563;">Thanksgiving holiday</td>
                                    <td style="padding: 12px;">
                                        <span style="padding: 6px 12px; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Approved</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; color: #4b5563;">Personal</td>
                                    <td style="padding: 12px; color: #4b5563;">Nov 5, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">Nov 5, 2025</td>
                                    <td style="padding: 12px; color: #4b5563;">1</td>
                                    <td style="padding: 12px; color: #4b5563;">Doctor appointment</td>
                                    <td style="padding: 12px;">
                                        <span style="padding: 6px 12px; background: #fee2e2; color: #991b1b; border-radius: 20px; font-size: 12px; font-weight: 600;">Rejected</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
            // Toggle handled by inline onclick
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
