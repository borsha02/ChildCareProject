<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/parentdashboard.css'])
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
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>Parent Account</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('parent.dashboard') }}" class="nav-item active">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('parent.child-profile') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Profile</span>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="nav-item">
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
                        <span class="badge">5</span>
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
                    <a href="{{ route('parent.child-profile') }}" class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-child"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $childrenCount }}</h3>
                            <p>Registered Children</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>94%</h3>
                            <p>Attendance Rate</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="stat-details">
                            <h3>$450</h3>
                            <p>Pending Payment</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.events') }}" class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-details">
                            <h3>3</h3>
                            <p>Upcoming Events</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="stat-card">
                        <div class="stat-icon pink">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stat-details">
                            <h3>2</h3>
                            <p>Assigned Caregivers</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.health') }}" class="stat-card">
                        <div class="stat-icon cyan">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="stat-details">
                            <h3>2</h3>
                            <p>Health Records</p>
                        </div>
                    </a>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- My Children -->
                    <div class="card">
                        <div class="card-header">
                            <h3>My Children</h3>
                            <a href="{{ route('parent.child-profile') }}" class="view-all">View All</a>
                        </div>
                        <div class="children-list">
                            <div class="child-item">
                                <div class="child-avatar">EM</div>
                                <div class="child-info">
                                    <h4>Emma Doe</h4>
                                    <p>Age: 4 years • Class: Preschool A</p>
                                </div>
                                <span class="status-badge present">Present</span>
                            </div>
                            <div class="child-item">
                                <div class="child-avatar"
                                    style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                <div class="child-info">
                                    <h4>Lucas James</h4>
                                    <p>Age: 3 years • Class: Toddler B</p>
                                </div>
                                <span class="status-badge present">Present</span>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Upcoming Events</h3>
                            <a href="{{ route('parent.events') }}" class="view-all">View All</a>
                        </div>
                        <div class="event-list">
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="day">25</div>
                                    <div class="month">DEC</div>
                                </div>
                                <div class="event-info">
                                    <h4>Christmas Party</h4>
                                    <p>10:00 AM - Main Hall</p>
                                </div>
                            </div>
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="day">28</div>
                                    <div class="month">DEC</div>
                                </div>
                                <div class="event-info">
                                    <h4>Parent-Teacher Meeting</h4>
                                    <p>2:00 PM - Conference Room</p>
                                </div>
                            </div>
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="day">01</div>
                                    <div class="month">JAN</div>
                                </div>
                                <div class="event-info">
                                    <h4>New Year Celebration</h4>
                                    <p>11:00 AM - Main Hall</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="content-grid">
                    <!-- Today's Activities -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Today's Activities</h3>
                            <a href="{{ route('parent.reports') }}" class="view-all">View Details</a>
                        </div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon meal">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Breakfast Completed</h4>
                                    <p>Emma had oatmeal and fruits</p>
                                </div>
                                <div class="activity-time">8:30 AM</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon play">
                                    <i class="fas fa-palette"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Art Activity</h4>
                                    <p>Lucas participated in painting class</p>
                                </div>
                                <div class="activity-time">10:00 AM</div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon nap">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Nap Time</h4>
                                    <p>Both children are currently napping</p>
                                </div>
                                <div class="activity-time">12:30 PM</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="quick-actions">
                            <a href="{{ route('parent.messages') }}" class="action-btn">
                                <i class="fas fa-comment-dots"></i>
                                <p>Send Message</p>
                            </a>
                            <a href="{{ route('parent.invoice') }}" class="action-btn">
                                <i class="fas fa-credit-card"></i>
                                <p>Pay Invoice</p>
                            </a>
                            <a href="{{ route('parent.reports') }}" class="action-btn">
                                <i class="fas fa-download"></i>
                                <p>Download Report</p>
                            </a>
                            <a href="{{ route('parent.health') }}" class="action-btn">
                                <i class="fas fa-notes-medical"></i>
                                <p>Health Records</p>
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
