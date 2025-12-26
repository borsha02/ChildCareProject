<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule - Caregiver Dashboard</title>
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
                    <a href="{{ route('caregiver.schedule') }}" class="nav-item active">
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
                <h1>My Schedule</h1>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search schedule...">
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
                <!-- Week Navigation -->
                <div class="card" style="margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <button style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-chevron-left"></i> Previous Week
                        </button>
                        <h3 style="color: #1f2937; font-size: 20px;">Week of December 23 - 29, 2025</h3>
                        <button style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer;">
                            Next Week <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Monday -->
                <div class="card" style="margin-bottom: 20px;">
                    <div class="card-header">
                        <h3>Monday, December 23</h3>
                        <span style="color: #059669; font-weight: 600;">Today</span>
                    </div>
                    <div class="schedule-list">
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">8:00</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Morning Arrival & Check-in</h4>
                                <p>Welcome children and parents • Preschool A</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">9:00</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Morning Circle Time</h4>
                                <p>Songs, stories, and calendar • Preschool A</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">10:00</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Snack Time</h4>
                                <p>Healthy snacks and drinks • All Classes</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">10:30</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Art Activity - Painting</h4>
                                <p>Creative expression time • All Classes</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">12:00</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Lunch Time</h4>
                                <p>Supervised meal time • Cafeteria</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">1:00</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Nap Time</h4>
                                <p>Quiet rest period • Toddler Classes</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">2:00</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Outdoor Play</h4>
                                <p>Physical activity and games • Playground</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">3:30</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Afternoon Snack</h4>
                                <p>Light refreshments • All Classes</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">4:00</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Free Play & Pick-up</h4>
                                <p>Indoor activities until parent arrival • All Classes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tuesday -->
                <div class="card" style="margin-bottom: 20px;">
                    <div class="card-header">
                        <h3>Tuesday, December 24</h3>
                    </div>
                    <div class="schedule-list">
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">8:00</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Morning Arrival & Check-in</h4>
                                <p>Welcome children and parents • Preschool A</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">9:00</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Music & Movement</h4>
                                <p>Dance and rhythm activities • All Classes</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">10:30</div>
                                <div class="period">AM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Science Exploration</h4>
                                <p>Hands-on experiments • Preschool A</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">12:00</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Lunch Time</h4>
                                <p>Supervised meal time • Cafeteria</p>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time">2:00</div>
                                <div class="period">PM</div>
                            </div>
                            <div class="schedule-info">
                                <h4>Story Time</h4>
                                <p>Reading and comprehension • All Classes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wednesday -->
                <div class="card" style="margin-bottom: 20px;">
                    <div class="card-header">
                        <h3>Wednesday, December 25</h3>
                        <span style="color: #ef4444; font-weight: 600;">Holiday - Closed</span>
                    </div>
                    <div style="padding: 40px; text-align: center; color: #6b7280;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 15px; color: #d1d5db;"></i>
                        <p style="font-size: 16px;">Facility closed for Christmas Day</p>
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
