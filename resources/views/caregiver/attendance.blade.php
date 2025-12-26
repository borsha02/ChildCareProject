<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Caregiver Dashboard</title>
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
                    <a href="{{ route('caregiver.attendance') }}" class="nav-item active">
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
                    <a href="{{ route('caregiver.leave') }}" class="nav-item">
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
                <h1>Attendance Tracking</h1>
                <div class="top-bar-actions">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="date" value="2025-12-23" style="padding: 10px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                        <button style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                            <i class="fas fa-save"></i> Save All
                        </button>
                    </div>
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
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>10/12</h3>
                            <p>Present Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="stat-details">
                            <h3>2</h3>
                            <p>Absent Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-details">
                            <h3>83%</h3>
                            <p>Attendance Rate</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-details">
                            <h3>1</h3>
                            <p>Late Arrivals</p>
                        </div>
                    </div>
                </div>

                <!-- Attendance Marking -->
                <div class="card">
                    <div class="card-header">
                        <h3>Mark Attendance - December 23, 2025</h3>
                        <div style="display: flex; gap: 10px;">
                            <button style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px;">
                                <i class="fas fa-check"></i> Mark All Present
                            </button>
                            <button style="padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px;">
                                <i class="fas fa-times"></i> Mark All Absent
                            </button>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Child Name</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Class</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Check-in Time</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Check-out Time</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Status</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="child-avatar" style="width: 40px; height: 40px; font-size: 14px;">EM</div>
                                            <span style="color: #1f2937; font-weight: 500;">Emma Martinez</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; color: #4b5563;">Preschool A</td>
                                    <td style="padding: 12px;">
                                        <input type="time" value="08:15" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="time" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <select style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; background: #d1fae5; color: #065f46; font-weight: 600;">
                                            <option>Present</option>
                                            <option>Absent</option>
                                            <option>Late</option>
                                            <option>Excused</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="text" placeholder="Add notes..." style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; width: 150px;">
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="child-avatar" style="width: 40px; height: 40px; font-size: 14px; background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                            <span style="color: #1f2937; font-weight: 500;">Lucas Johnson</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; color: #4b5563;">Toddler B</td>
                                    <td style="padding: 12px;">
                                        <input type="time" value="08:00" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="time" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <select style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; background: #d1fae5; color: #065f46; font-weight: 600;">
                                            <option>Present</option>
                                            <option>Absent</option>
                                            <option>Late</option>
                                            <option>Excused</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="text" placeholder="Add notes..." style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; width: 150px;">
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="child-avatar" style="width: 40px; height: 40px; font-size: 14px; background: linear-gradient(135deg, #f59e0b, #d97706);">OW</div>
                                            <span style="color: #1f2937; font-weight: 500;">Olivia Williams</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; color: #4b5563;">Preschool A</td>
                                    <td style="padding: 12px;">
                                        <input type="time" value="08:45" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="time" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <select style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; background: #fef3c7; color: #92400e; font-weight: 600;">
                                            <option>Late</option>
                                            <option>Present</option>
                                            <option>Absent</option>
                                            <option>Excused</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="text" value="Traffic delay" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; width: 150px;">
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="child-avatar" style="width: 40px; height: 40px; font-size: 14px; background: linear-gradient(135deg, #8b5cf6, #7c3aed);">NB</div>
                                            <span style="color: #1f2937; font-weight: 500;">Noah Brown</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; color: #4b5563;">Toddler A</td>
                                    <td style="padding: 12px;">
                                        <input type="time" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="time" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <select style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; background: #fee2e2; color: #991b1b; font-weight: 600;">
                                            <option>Absent</option>
                                            <option>Present</option>
                                            <option>Late</option>
                                            <option>Excused</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="text" value="Sick" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; width: 150px;">
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="child-avatar" style="width: 40px; height: 40px; font-size: 14px; background: linear-gradient(135deg, #10b981, #059669);">AD</div>
                                            <span style="color: #1f2937; font-weight: 500;">Ava Davis</span>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; color: #4b5563;">Preschool A</td>
                                    <td style="padding: 12px;">
                                        <input type="time" value="08:10" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="time" style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                    </td>
                                    <td style="padding: 12px;">
                                        <select style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; background: #d1fae5; color: #065f46; font-weight: 600;">
                                            <option>Present</option>
                                            <option>Absent</option>
                                            <option>Late</option>
                                            <option>Excused</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px;">
                                        <input type="text" placeholder="Add notes..." style="padding: 6px 10px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px; width: 150px;">
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
