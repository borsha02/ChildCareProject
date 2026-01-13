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
                    <button class="filter-tab" data-filter="past">Past Events</button>
                    <button class="filter-tab" data-filter="children-registered">Registered Children</button>
                </div>
                <div class="filter-actions">
                    <select class="filter-select" id="categoryFilter">
                        <option value="all">All Categories</option>
                        <option value="educational">Educational</option>
                        <option value="sports">Sports & Recreation</option>
                        <option value="cultural">Cultural</option>
                        <option value="training">Staff Training</option>
                        <option value="social">Social Events</option>
                    </select>
                </div>
            </div>


            <!-- Events List -->
            <div class="events-section">
                <h2><i class="fas fa-calendar-alt"></i> All Events</h2>

                @forelse($events as $event)
                    @php
                        $isPast = $event->end_time->isPast();
                        
                        $hasChildren = false;
                        foreach($event->registrations as $reg) {
                            if($reg->user && $reg->user->role === 'parent' && $reg->user->children->isNotEmpty()) {
                                $hasChildren = true;
                                break;
                            }
                        }

                        $filterClass = $isPast ? 'past' : 'upcoming';
                        if($hasChildren) $filterClass .= ' children-registered';
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
                                @if($event->location)
                                    <span class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                                    </span>
                                @endif
                                <span class="event-detail">
                                    <i class="fas fa-users"></i> {{ $event->registrations_count }} {{ Str::plural('Attendee', $event->registrations_count) }}
                                </span>
                            </div>
                        </div>
                        <div class="event-actions">
                            @if($event->registrations_count > 0)
                                <button class="action-btn secondary" onclick="showAttendees({{ $event->id }})">
                                    <i class="fas fa-users"></i> Attendees
                                </button>
                                <button class="action-btn primary" onclick="showChildren({{ $event->id }})" style="background: #fbbf24; color: #1f2937; border: none;">
                                    <i class="fas fa-child"></i> Children List
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="no-events" style="text-align: center; padding: 60px 20px; color: #6b7280;">
                        <i class="fas fa-calendar-times" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px; display: block;"></i>
                        <p style="font-size: 18px; margin: 0;">No events found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Attendees Modal -->
    <div class="modal-overlay" id="attendeesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalEventTitle">Event Attendees</h2>
                <button class="close-modal" onclick="closeAttendeesModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="attendeesListContainer">
                <!-- Attendees list will be populated here -->
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
                    if (activeTab === 'children-registered' && !event.classList.contains('children-registered')) show = false;

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

        // Events data for modal
        const eventsData = @json($events);

        function showAttendees(eventId) {
            const event = eventsData.find(e => e.id === eventId);
            if (!event) return;

            document.getElementById('modalEventTitle').textContent = event.title + ' - Attendees';
            const container = document.getElementById('attendeesListContainer');
            
            if (event.registrations && event.registrations.length > 0) {
                let html = '<div class="attendees-list">';
                event.registrations.forEach((registration, index) => {
                    const user = registration.user;
                    html += `
                        <div class="attendee-item">
                            <div class="attendee-avatar">${user.name.charAt(0).toUpperCase()}</div>
                            <div class="attendee-info">
                                <h4>${user.name}</h4>
                                <p>${user.email || ''}</p>
                                <span class="attendee-role">${user.role === 'parent' ? 'Parent' : 'Caregiver'}</span>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 20px;">No registrations yet.</p>';
            }

            document.getElementById('attendeesModal').classList.add('active');
        }

        function showChildren(eventId) {
            const event = eventsData.find(e => e.id === eventId);
            if (!event) return;

            document.getElementById('modalEventTitle').textContent = event.title + ' - Registered Children';
            const container = document.getElementById('attendeesListContainer');
            
            let childrenHtml = '';
            let childrenCount = 0;

            if (event.registrations) {
                event.registrations.forEach(registration => {
                    if (registration.user && registration.user.role === 'parent' && registration.user.children) {
                        registration.user.children.forEach(child => {
                            childrenCount++;
                            const childName = `${child.first_name} ${child.last_name}`;
                            childrenHtml += `
                                <div class="attendee-item">
                                    <div class="attendee-avatar" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                                        <i class="fas fa-child"></i>
                                    </div>
                                    <div class="attendee-info">
                                        <h4>${childName}</h4>
                                        <p>Parent: ${registration.user.name}</p>
                                        <span class="attendee-role" style="background: #fef3c7; color: #92400e;">Child</span>
                                    </div>
                                </div>
                            `;
                        });
                    }
                });
            }

            if (childrenCount > 0) {
                container.innerHTML = `<div class="attendees-list">${childrenHtml}</div>`;
            } else {
                container.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 20px;">No children registered for this event.</p>';
            }

            document.getElementById('attendeesModal').classList.add('active');
        }

        function closeAttendeesModal() {
            document.getElementById('attendeesModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('attendeesModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAttendeesModal();
            }
        });
    </script>
@endsection
