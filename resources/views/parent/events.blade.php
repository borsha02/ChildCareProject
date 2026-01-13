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
                        @php
                            $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge">{{ $unreadMessages }}</span>
                        @endif
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
                            <select class="filter-select" id="categoryFilter">
                                <option value="all">All Categories</option>
                                <option value="educational">Educational</option>
                                <option value="sports">Sports & Recreation</option>
                                <option value="cultural">Cultural</option>
                                <option value="social">Social Events</option>
                                <option value="holiday">Holidays</option>
                            </select>
                        </div>
                    </div>

                    <!-- Events List -->
                    <div class="events-section">
                        <h2><i class="fas fa-calendar-alt"></i> All Events</h2>

                        @forelse($events as $event)
                            @php
                                $isPast = $event->end_time->isPast();
                                $isRegistered = $event->registrations->isNotEmpty();
                                $filterClass = $isPast ? 'past' : 'upcoming';
                                if($isRegistered) $filterClass .= ' registered';
                            @endphp

                            <div class="event-card {{ $filterClass }} {{ $event->category }}" data-category="{{ $event->category }}">
                                <div class="event-date-badge">
                                    <div class="date-day">{{ $event->start_time->format('d') }}</div>
                                    <div class="date-month">{{ $event->start_time->format('M') }}</div>
                                </div>
                                <div class="event-content">
                                    <div class="event-header">
                                        <h3>{{ $event->title }}</h3>
                                        <span class="event-category {{ $event->category }}">{{ ucfirst($event->category) }}</span>
                                    </div>
                                    <p class="event-description">
                                        {{ Str::limit($event->description, 100) }}
                                    </p>
                                    <div class="event-details">
                                        <span class="event-detail">
                                            <i class="fas fa-clock"></i> {{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}
                                        </span>
                                        <span class="event-detail">
                                            <i class="fas fa-map-marker-alt"></i> {{ $event->location ?? 'TBD' }}
                                        </span>
                                        @if($event->capacity)
                                            <span class="event-detail">
                                                <i class="fas fa-users"></i> {{ $event->registrations_count ?? 0 }} / {{ $event->capacity }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="event-actions">
                                    @if($isRegistered)
                                        <button class="action-btn secondary" disabled>
                                            <i class="fas fa-check-circle"></i> Registered
                                        </button>
                                    @elseif(!$isPast)
                                        <button class="action-btn primary">
                                            <i class="fas fa-calendar-plus"></i> Register
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="no-events">
                                <p>No events found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const events = document.querySelectorAll('.event-card');
            const filterTabs = document.querySelectorAll('.filter-tab');
            const categoryFilter = document.getElementById('categoryFilter');

            function filterEvents() {
                const activeTab = document.querySelector('.filter-tab.active').dataset.filter;
                const category = categoryFilter.value;

                events.forEach(event => {
                    let show = true;

                    // Tab filter
                    if (activeTab === 'upcoming' && event.classList.contains('past')) show = false;
                    if (activeTab === 'past' && !event.classList.contains('past')) show = false;
                    if (activeTab === 'registered' && !event.classList.contains('registered')) show = false;

                    // Category filter
                    if (category !== 'all' && event.dataset.category !== category) show = false;

                    event.style.display = show ? 'flex' : 'none'; // Assuming flex layout
                });
            }

            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    filterEvents();
                });
            });

            if (categoryFilter) {
                categoryFilter.addEventListener('change', filterEvents);
            }
            
            // Initial filter
            filterEvents();
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
                if (sidebar && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
