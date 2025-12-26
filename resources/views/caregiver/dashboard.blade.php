<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Dashboard - Childcare Management</title>
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
                    <a href="{{ route('caregiver.dashboard') }}" class="nav-item active">
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
                <h1>Dashboard Overview</h1>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
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
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h3>12</h3>
                            <p>Assigned Children</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>10</h3>
                            <p>Present Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stat-details">
                            <h3>5</h3>
                            <p>Pending Tasks</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-details">
                            <h3>4</h3>
                            <p>New Messages</p>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Assigned Children -->
                    <div class="card">
                        <div class="card-header">
                            <h3>My Assigned Children</h3>
                            <a href="{{ route('caregiver.assigned') }}" class="view-all">View All</a>
                        </div>
                        <div class="children-list">
                            <div class="child-item">
                                <div class="child-avatar">EM</div>
                                <div class="child-info">
                                    <h4>Emma Martinez</h4>
                                    <p>Age: 4 years • Class: Preschool A</p>
                                </div>
                                <span class="status-badge present">Present</span>
                            </div>
                            <div class="child-item">
                                <div class="child-avatar"
                                    style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                <div class="child-info">
                                    <h4>Lucas Johnson</h4>
                                    <p>Age: 3 years • Class: Toddler B</p>
                                </div>
                                <span class="status-badge present">Present</span>
                            </div>
                            <div class="child-item">
                                <div class="child-avatar"
                                    style="background: linear-gradient(135deg, #f59e0b, #d97706);">OW</div>
                                <div class="child-info">
                                    <h4>Olivia Williams</h4>
                                    <p>Age: 5 years • Class: Preschool A</p>
                                </div>
                                <span class="status-badge present">Present</span>
                            </div>
                            <div class="child-item">
                                <div class="child-avatar"
                                    style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">NB</div>
                                <div class="child-info">
                                    <h4>Noah Brown</h4>
                                    <p>Age: 2 years • Class: Toddler A</p>
                                </div>
                                <span class="status-badge absent">Absent</span>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Schedule -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Today's Schedule</h3>
                            <a href="{{ route('caregiver.schedule') }}" class="view-all">View Full</a>
                        </div>
                        <div class="schedule-list">
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <div class="time">9:00</div>
                                    <div class="period">AM</div>
                                </div>
                                <div class="schedule-info">
                                    <h4>Morning Circle Time</h4>
                                    <p>Preschool A</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <div class="time">10:30</div>
                                    <div class="period">AM</div>
                                </div>
                                <div class="schedule-info">
                                    <h4>Art Activity</h4>
                                    <p>All Classes</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <div class="time">12:00</div>
                                    <div class="period">PM</div>
                                </div>
                                <div class="schedule-info">
                                    <h4>Lunch Time</h4>
                                    <p>Cafeteria</p>
                                </div>
                            </div>
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <div class="time">2:00</div>
                                    <div class="period">PM</div>
                                </div>
                                <div class="schedule-info">
                                    <h4>Outdoor Play</h4>
                                    <p>Playground</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="content-grid">
                    <!-- Today's Tasks -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Today's Tasks</h3>
                            <a href="{{ route('caregiver.reports') }}" class="view-all">View All</a>
                        </div>
                        <div class="task-list">
                            <div class="task-item">
                                <div class="task-checkbox"></div>
                                <div class="task-content">
                                    <p>Complete daily report for Emma Martinez</p>
                                    <span>Due: 4:00 PM</span>
                                </div>
                                <span class="task-priority high">High</span>
                            </div>
                            <div class="task-item">
                                <div class="task-checkbox"></div>
                                <div class="task-content">
                                    <p>Update health records for Lucas Johnson</p>
                                    <span>Due: 3:00 PM</span>
                                </div>
                                <span class="task-priority medium">Medium</span>
                            </div>
                            <div class="task-item">
                                <div class="task-checkbox"></div>
                                <div class="task-content">
                                    <p>Respond to parent messages</p>
                                    <span>Due: End of day</span>
                                </div>
                                <span class="task-priority medium">Medium</span>
                            </div>
                            <div class="task-item">
                                <div class="task-checkbox"></div>
                                <div class="task-content">
                                    <p>Prepare materials for tomorrow's art class</p>
                                    <span>Due: Tomorrow</span>
                                </div>
                                <span class="task-priority low">Low</span>
                            </div>
                            <div class="task-item">
                                <div class="task-checkbox"></div>
                                <div class="task-content">
                                    <p>Review attendance records</p>
                                    <span>Due: End of week</span>
                                </div>
                                <span class="task-priority low">Low</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="quick-actions">
                            <a href="{{ route('caregiver.attendance') }}" class="action-btn">
                                <i class="fas fa-calendar-check"></i>
                                <p>Mark Attendance</p>
                            </a>
                            <a href="{{ route('caregiver.reports') }}" class="action-btn">
                                <i class="fas fa-file-alt"></i>
                                <p>Create Report</p>
                            </a>
                            <a href="{{ route('caregiver.messages') }}" class="action-btn">
                                <i class="fas fa-comment-dots"></i>
                                <p>Send Message</p>
                            </a>
                            <a href="{{ route('caregiver.health') }}" class="action-btn">
                                <i class="fas fa-notes-medical"></i>
                                <p>Health Update</p>
                            </a>
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
