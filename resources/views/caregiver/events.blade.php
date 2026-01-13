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
        <div style="display: flex; align-items: center;">
            <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Events & Activities</h1>
        </div>
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

                @forelse($events as $event)
                    @php
                        $isPast = $event->end_time->isPast();
                        $isRegistered = $event->registrations->isNotEmpty();
                        $filterClass = $isPast ? 'past' : 'upcoming';
                        if($isRegistered) $filterClass .= ' my-events'; // using 'my-events' as the filter key
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const events = document.querySelectorAll('.event-card');
            const filterTabs = document.querySelectorAll('.filter-tab');
            const categoryFilter = document.querySelector('.filter-select');

            function filterEvents() {
                const activeTab = document.querySelector('.filter-tab.active').dataset.filter;
                const category = categoryFilter.value;

                events.forEach(event => {
                    let show = true;

                    // Tab filter
                    if (activeTab === 'upcoming' && event.classList.contains('past')) show = false;
                    if (activeTab === 'past' && !event.classList.contains('past')) show = false;
                    if (activeTab === 'my-events' && !event.classList.contains('my-events')) show = false;

                    // Category filter
                    if (category !== 'all' && event.dataset.category !== category) show = false;

                    event.style.display = show ? 'flex' : 'none';
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

            // Mobile menu toggle
            const mobileToggle = document.querySelector('.mobile-toggle');
            const sidebar = document.getElementById('sidebar');

            if (mobileToggle) {
                mobileToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Initial filter
            filterEvents();
        });
    </script>
@endsection
