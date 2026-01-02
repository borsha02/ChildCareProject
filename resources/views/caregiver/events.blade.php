@extends('layouts.caregiver')

@section('title', 'Events & Activities')

@section('styles')
    @vite(['resources/css/caregiver/events.css'])
@endsection

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Events & Activities</h1>
        <div class="top-bar-actions">
            <div class="search-box">
                <input type="text" placeholder="Search events...">
                <i class="fas fa-search"></i>
            </div>
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="notification-dot"></span>
            </a>
            <a href="{{ route('caregiver.messages') }}"
                class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        <div class="events-container">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">All Events</button>
                    <button class="filter-tab" data-filter="upcoming">Upcoming</button>
                    <button class="filter-tab" data-filter="my-events">My Events</button>
                    <button class="filter-tab" data-filter="past">Past Events</button>
                </div>
                <div class="filter-actions">
                    <select class="filter-select">
                        <option value="all">All Categories</option>
                        <option value="educational">Educational</option>
                        <option value="sports">Sports & Recreation</option>
                        <option value="cultural">Cultural</option>
                        <option value="training">Staff Training</option>
                        <option value="social">Social Events</option>
                    </select>
                    <button class="create-event-btn">
                        <i class="fas fa-plus"></i> Create Event
                    </button>
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
                            <div class="featured-role">
                                <i class="fas fa-user-tag"></i> Role: Event Coordinator
                            </div>
                            <button class="register-btn assigned">
                                <i class="fas fa-check-circle"></i> Assigned
                            </button>
                        </div>
                    </div>

                    <div class="featured-card training">
                        <div class="featured-badge">Next Week</div>
                        <div class="featured-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="featured-content">
                            <h3>First Aid Training</h3>
                            <p class="featured-date">
                                <i class="fas fa-calendar"></i> January 3, 2026
                            </p>
                            <p class="featured-time">
                                <i class="fas fa-clock"></i> 9:00 AM - 12:00 PM
                            </p>
                            <p class="featured-location">
                                <i class="fas fa-map-marker-alt"></i> Training Room
                            </p>
                            <div class="featured-role">
                                <i class="fas fa-user-tag"></i> Mandatory Attendance
                            </div>
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
                            Individual sessions with parents to discuss children's progress and development. Prepare reports
                            for your assigned children.
                        </p>
                        <div class="event-details">
                            <span class="event-detail">
                                <i class="fas fa-clock"></i> 2:00 PM - 5:00 PM
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-map-marker-alt"></i> Conference Room
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-users"></i> 12 Children
                            </span>
                        </div>
                        <div class="event-role-tag">
                            <i class="fas fa-user-tag"></i> Your Role: Lead Presenter
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="action-btn primary">
                            <i class="fas fa-clipboard-check"></i> View Schedule
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
                            Organize and supervise outdoor activities and games for children. Ensure safety protocols are
                            followed.
                        </p>
                        <div class="event-details">
                            <span class="event-detail">
                                <i class="fas fa-clock"></i> 9:00 AM - 3:00 PM
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-map-marker-alt"></i> Sports Ground
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-users"></i> 50 Children
                            </span>
                        </div>
                        <div class="event-role-tag">
                            <i class="fas fa-user-tag"></i> Your Role: Activity Supervisor
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="action-btn primary">
                            <i class="fas fa-calendar-plus"></i> Confirm Attendance
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
                            Help set up and manage the showcase of children's artwork. Assist parents and guide them through
                            the exhibition.
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
                        <div class="event-role-tag">
                            <i class="fas fa-user-tag"></i> Your Role: Exhibition Guide
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="action-btn primary">
                            <i class="fas fa-calendar-plus"></i> Volunteer
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>

                <!-- Event Card 4 -->
                <div class="event-card">
                    <div class="event-date-badge">
                        <div class="date-day">15</div>
                        <div class="date-month">JAN</div>
                    </div>
                    <div class="event-content">
                        <div class="event-header">
                            <h3>CPR & Safety Workshop</h3>
                            <span class="event-category training">Training</span>
                        </div>
                        <p class="event-description">
                            Mandatory training session for all caregivers. Renew CPR certification and learn updated safety
                            protocols.
                        </p>
                        <div class="event-details">
                            <span class="event-detail">
                                <i class="fas fa-clock"></i> 1:00 PM - 4:00 PM
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-map-marker-alt"></i> Training Center
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-users"></i> 15 Staff
                            </span>
                        </div>
                        <div class="event-role-tag mandatory">
                            <i class="fas fa-exclamation-circle"></i> Mandatory Attendance
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="action-btn primary">
                            <i class="fas fa-calendar-check"></i> Confirm
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>

                <!-- Event Card 5 -->
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
                            Organize games and activities for families. Ensure children's safety and facilitate family
                            engagement activities.
                        </p>
                        <div class="event-details">
                            <span class="event-detail">
                                <i class="fas fa-clock"></i> 11:00 AM - 5:00 PM
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-map-marker-alt"></i> Central Park
                            </span>
                            <span class="event-detail">
                                <i class="fas fa-users"></i> 75 Families
                            </span>
                        </div>
                        <div class="event-role-tag">
                            <i class="fas fa-user-tag"></i> Your Role: Activity Coordinator
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="action-btn primary">
                            <i class="fas fa-calendar-plus"></i> Sign Up
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>

                <!-- Event Card 6 - Past Event -->
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
                            Successfully coordinated children's performances. Great job to all staff members who
                            participated!
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
                        <div class="event-role-tag completed">
                            <i class="fas fa-check-circle"></i> Completed
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="action-btn secondary">
                            <i class="fas fa-images"></i> View Photos
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-file-alt"></i> Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
    </script>
@endsection
