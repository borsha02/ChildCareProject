<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Caregiver Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/caregiver/notifications.css'])
</head>
<body>
    <div class="dashboard-container">
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
                    <a href="{{ route('caregiver.notifications') }}" class="nav-item active">
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
                <h1>Notifications</h1>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search notifications...">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('caregiver.notifications') }}" class="icon-btn active">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </a>
                    <a href="{{ route('caregiver.messages') }}" class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <div class="notifications-container">
                    <div class="notifications-header">
                        <div class="header-left">
                            <h2>All Notifications</h2>
                            <span class="badge">5 New</span>
                        </div>
                        <div class="header-actions">
                            <button class="mark-read-btn">Mark all as read</button>
                            <select class="filter-select">
                                <option value="all">All</option>
                                <option value="unread">Unread</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="notification-list">
                        <!-- Unread & High Priority -->
                        <div class="notification-item unread high-priority">
                            <div class="notification-icon warning">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-top">
                                    <h4>Emergency Drill Tomorrow</h4>
                                    <span class="time">10 mins ago</span>
                                </div>
                                <p>There will be a mandatory fire drill tomorrow at 10:00 AM. Please ensure all children are prepared.</p>
                                <div class="notification-tags">
                                    <span class="tag urgent">Urgent</span>
                                    <span class="tag admin">Admin</span>
                                </div>
                            </div>
                            <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                        </div>

                        <!-- Unread -->
                        <div class="notification-item unread">
                            <div class="notification-icon message">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-top">
                                    <h4>New Message from Mrs. Johnson</h4>
                                    <span class="time">1 hour ago</span>
                                </div>
                                <p>Regarding Lucas's medication schedule for next week. Please review the updated health record.</p>
                                <div class="notification-tags">
                                    <span class="tag message">Message</span>
                                </div>
                            </div>
                            <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                        </div>

                        <!-- Read -->
                        <div class="notification-item">
                            <div class="notification-icon event">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-top">
                                    <h4>Event Reminder: Christmas Party</h4>
                                    <span class="time">3 hours ago</span>
                                </div>
                                <p>The Christmas Party is scheduled for tomorrow. Setup begins at 8:30 AM in the Main Hall.</p>
                                <div class="notification-tags">
                                    <span class="tag event">Event</span>
                                </div>
                            </div>
                            <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                        </div>

                        <div class="notification-item">
                            <div class="notification-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-top">
                                    <h4>Daily Report Submitted</h4>
                                    <span class="time">Yesterday</span>
                                </div>
                                <p>Your daily report for Class A has been successfully submitted and approved by the supervisor.</p>
                                <div class="notification-tags">
                                    <span class="tag system">System</span>
                                </div>
                            </div>
                            <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                        </div>

                        <div class="notification-item">
                            <div class="notification-icon info">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-top">
                                    <h4>Policy Update: Pick-up Procedures</h4>
                                    <span class="time">2 days ago</span>
                                </div>
                                <p>New safety protocols for child pick-up will be effective starting next Monday. Please review the handbook.</p>
                                <div class="notification-tags">
                                    <span class="tag admin">Policy</span>
                                </div>
                            </div>
                            <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                        </div>
                    </div>

                    <div class="pagination">
                        <button class="page-btn disabled"><i class="fas fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
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
