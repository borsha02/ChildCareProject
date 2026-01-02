<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/events.css'])
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
                        <span class="badge">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item active">
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
                    <h1>Events & Activities</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search events...">
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
                <div class="events-container">
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">All Events</button>
                            <button class="filter-tab" data-filter="upcoming">Upcoming</button>
                            <button class="filter-tab" data-filter="past">Past Events</button>
                            <button class="filter-tab" data-filter="registered">My Registrations</button>
                        </div>
                        <div class="filter-actions">
                            <select class="filter-select">
                                <option value="all">All Categories</option>
                                <option value="educational">Educational</option>
                                <option value="sports">Sports & Recreation</option>
                                <option value="cultural">Cultural</option>
                                <option value="social">Social Events</option>
                            </select>
                        </div>
                    </div>

                    <!-- Calendar View Toggle -->
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="list">
                            <i class="fas fa-list"></i> List View
                        </button>
                        <button class="view-btn" data-view="calendar">
                            <i class="fas fa-calendar"></i> Calendar View
                        </button>
                    </div>

                    <!-- Upcoming Events Highlight -->
                    <div class="featured-events">
                        <h2><i class="fas fa-star"></i> Featured Events</h2>
                        <div class="featured-grid">
                            <div class="featured-card christmas">
                                <div class="featured-badge">This Week</div>
                                <div class="featured-icon">
                                    <i class="fas fa-gifts"></i>
                                </div>
                                <div class="featured-content">
                                    <h3>Christmas Party</h3>
                                    <p class="featured-date">
                                        <i class="fas fa-calendar"></i> December 25, 2025
                                    </p>
                                    <p class="featured-time">
                                        <i class="fas fa-clock"></i> 10:00 AM - 2:00 PM
                                    </p>
                                    <p class="featured-location">
                                        <i class="fas fa-map-marker-alt"></i> Main Hall
                                    </p>
                                    <button class="register-btn registered">
                                        <i class="fas fa-check-circle"></i> Registered
                                    </button>
                                </div>
                            </div>

                            <div class="featured-card newyear">
                                <div class="featured-badge">Next Week</div>
                                <div class="featured-icon">
                                    <i class="fas fa-champagne-glasses"></i>
                                </div>
                                <div class="featured-content">
                                    <h3>New Year Celebration</h3>
                                    <p class="featured-date">
                                        <i class="fas fa-calendar"></i> January 1, 2026
                                    </p>
                                    <p class="featured-time">
                                        <i class="fas fa-clock"></i> 11:00 AM - 1:00 PM
                                    </p>
                                    <p class="featured-location">
                                        <i class="fas fa-map-marker-alt"></i> Main Hall
                                    </p>
                                    <button class="register-btn">
                                        <i class="fas fa-plus-circle"></i> Register Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Events List -->
                    <div class="events-section">
                        <h2><i class="fas fa-calendar-alt"></i> All Events</h2>

                        <!-- Event Card 1 -->
                        <div class="event-card">
                            <div class="event-date-badge">
                                <div class="date-day">28</div>
                                <div class="date-month">DEC</div>
                            </div>
                            <div class="event-content">
                                <div class="event-header">
                                    <h3>Parent-Teacher Meeting</h3>
                                    <span class="event-category educational">Educational</span>
                                </div>
                                <p class="event-description">
                                    Join us for an important discussion about your child's progress and development. Individual sessions will be scheduled.
                                </p>
                                <div class="event-details">
                                    <span class="event-detail">
                                        <i class="fas fa-clock"></i> 2:00 PM - 5:00 PM
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i> Conference Room
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-users"></i> 25 Attendees
                                    </span>
                                </div>
                            </div>
                            <div class="event-actions">
                                <button class="action-btn primary">
                                    <i class="fas fa-calendar-plus"></i> Register
                                </button>
                                <button class="action-btn secondary">
                                    <i class="fas fa-info-circle"></i> Details
                                </button>
                            </div>
                        </div>

                        <!-- Event Card 2 -->
                        <div class="event-card">
                            <div class="event-date-badge">
                                <div class="date-day">05</div>
                                <div class="date-month">JAN</div>
                            </div>
                            <div class="event-content">
                                <div class="event-header">
                                    <h3>Winter Sports Day</h3>
                                    <span class="event-category sports">Sports</span>
                                </div>
                                <p class="event-description">
                                    A fun-filled day of outdoor activities and games for children. Parents are welcome to join and cheer!
                                </p>
                                <div class="event-details">
                                    <span class="event-detail">
                                        <i class="fas fa-clock"></i> 9:00 AM - 3:00 PM
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i> Sports Ground
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-users"></i> 50 Attendees
                                    </span>
                                </div>
                            </div>
                            <div class="event-actions">
                                <button class="action-btn primary">
                                    <i class="fas fa-calendar-plus"></i> Register
                                </button>
                                <button class="action-btn secondary">
                                    <i class="fas fa-info-circle"></i> Details
                                </button>
                            </div>
                        </div>

                        <!-- Event Card 3 -->
                        <div class="event-card">
                            <div class="event-date-badge">
                                <div class="date-day">12</div>
                                <div class="date-month">JAN</div>
                            </div>
                            <div class="event-content">
                                <div class="event-header">
                                    <h3>Art Exhibition</h3>
                                    <span class="event-category cultural">Cultural</span>
                                </div>
                                <p class="event-description">
                                    Showcase of children's artwork from the past semester. Come celebrate your child's creativity!
                                </p>
                                <div class="event-details">
                                    <span class="event-detail">
                                        <i class="fas fa-clock"></i> 10:00 AM - 4:00 PM
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i> Art Gallery
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-users"></i> 100 Attendees
                                    </span>
                                </div>
                            </div>
                            <div class="event-actions">
                                <button class="action-btn primary">
                                    <i class="fas fa-calendar-plus"></i> Register
                                </button>
                                <button class="action-btn secondary">
                                    <i class="fas fa-info-circle"></i> Details
                                </button>
                            </div>
                        </div>

                        <!-- Event Card 4 -->
                        <div class="event-card">
                            <div class="event-date-badge">
                                <div class="date-day">20</div>
                                <div class="date-month">JAN</div>
                            </div>
                            <div class="event-content">
                                <div class="event-header">
                                    <h3>Family Picnic Day</h3>
                                    <span class="event-category social">Social</span>
                                </div>
                                <p class="event-description">
                                    A relaxing day out with families. Bring your picnic baskets and enjoy games, music, and quality time together.
                                </p>
                                <div class="event-details">
                                    <span class="event-detail">
                                        <i class="fas fa-clock"></i> 11:00 AM - 5:00 PM
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i> Central Park
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-users"></i> 75 Attendees
                                    </span>
                                </div>
                            </div>
                            <div class="event-actions">
                                <button class="action-btn primary">
                                    <i class="fas fa-calendar-plus"></i> Register
                                </button>
                                <button class="action-btn secondary">
                                    <i class="fas fa-info-circle"></i> Details
                                </button>
                            </div>
                        </div>

                        <!-- Event Card 5 -->
                        <div class="event-card past">
                            <div class="event-date-badge">
                                <div class="date-day">15</div>
                                <div class="date-month">DEC</div>
                            </div>
                            <div class="event-content">
                                <div class="event-header">
                                    <h3>Holiday Concert</h3>
                                    <span class="event-category cultural">Cultural</span>
                                </div>
                                <p class="event-description">
                                    Children performed beautiful holiday songs and dances. Thank you to all who attended!
                                </p>
                                <div class="event-details">
                                    <span class="event-detail">
                                        <i class="fas fa-clock"></i> 3:00 PM - 5:00 PM
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i> Auditorium
                                    </span>
                                    <span class="event-detail">
                                        <i class="fas fa-users"></i> 120 Attended
                                    </span>
                                </div>
                            </div>
                            <div class="event-actions">
                                <button class="action-btn secondary">
                                    <i class="fas fa-images"></i> View Photos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                // Filter events based on data-filter attribute
            });
        });

        // View toggle
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // Switch between list and calendar view
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
