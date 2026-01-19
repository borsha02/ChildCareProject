@extends('layouts.caregiver')

@section('title', 'My Schedule')

@section('styles')
    @vite(['resources/css/caregiver/schedule.css'])
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
            <h1>My Schedule</h1>
        </div>
        <div class="top-bar-actions">
            <div class="search-box">
                <input type="text" placeholder="Search schedule...">
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
        <div class="schedule-grid">
            <!-- Left Column: Schedule -->
            <div class="schedule-main" id="schedule-content">
                <!-- Day Navigation -->
                <div class="card week-navigation-container">
                    <div class="week-navigation-content">
                        <a href="{{ route('caregiver.schedule', ['date' => $prevDate]) }}" class="btn-nav-week">
                            <i class="fas fa-chevron-left"></i> Previous Day
                        </a>
                        <h3 class="week-title">{{ $selectedDate->format('l, F d, Y') }}</h3>
                        <a href="{{ route('caregiver.schedule', ['date' => $nextDate]) }}" class="btn-nav-week">
                            Next Day <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card schedule-card">
                    <div class="card-header">
                        <h3>{{ $daySchedule['date']->format('l, F d') }}</h3>
                        @if($daySchedule['is_today'])
                            <span class="label-today">Today</span>
                        @endif
                        @if($daySchedule['is_holiday'])
                            <span class="label-holiday">{{ $daySchedule['holiday_name'] }}</span>
                        @elseif($daySchedule['is_weekend'])
                             <span class="label-holiday" style="color: #6b7280;">Off Day</span>
                        @endif
                    </div>

                    @if($daySchedule['is_holiday'])
                        <div class="holiday-state">
                            <i class="fas fa-calendar-times holiday-icon"></i>
                            <p class="holiday-text">Facility closed for {{ $daySchedule['holiday_name'] }}</p>
                        </div>
                    @else
                        @if(empty($daySchedule['items']))
                            <div class="holiday-state">
                                @if($daySchedule['is_weekend'])
                                    <i class="fas fa-couch holiday-icon" style="font-size: 32px;"></i>
                                    <p class="holiday-text">Off Day - No events scheduled.</p>
                                @else
                                    <i class="fas fa-bed holiday-icon" style="font-size: 32px;"></i>
                                    <p class="holiday-text">No schedule items for this day.</p>
                                @endif
                            </div>
                        @else
                            <div class="schedule-list">
                                @foreach($daySchedule['items'] as $item)
                                <div class="schedule-item">
                                    <div class="schedule-time">
                                        <div class="time-text">{{ \Carbon\Carbon::parse($item['time'])->format('g:i') }}</div>
                                        <div class="period-text">{{ \Carbon\Carbon::parse($item['time'])->format('A') }}</div>
                                    </div>
                                    <div class="schedule-info">
                                        <h4>{{ $item['title'] }}</h4>
                                        <p>{{ $item['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="schedule-sidebar">
                <!-- Operating Hours -->
                <div class="sidebar-card">
                    <div class="schedule-sidebar-header">
                        <h3><i class="fas fa-clock"></i> Operating Hours</h3>
                    </div>
                    <div class="timings-list">
                        <div class="timing-item">
                            <div class="timing-label"><i class="fas fa-door-open"></i> Facility Opens</div>
                            <div class="timing-value">{{ \Carbon\Carbon::parse($timings['opening_time'])->format('g:i A') }}</div>
                        </div>
                        <div class="timing-item">
                            <div class="timing-label"><i class="fas fa-utensils"></i> Breakfast</div>
                            <div class="timing-value">{{ \Carbon\Carbon::parse($timings['breakfast_time'])->format('g:i A') }}</div>
                        </div>
                        <div class="timing-item">
                            <div class="timing-label"><i class="fas fa-hamburger"></i> Lunch</div>
                            <div class="timing-value">{{ \Carbon\Carbon::parse($timings['lunch_time'])->format('g:i A') }}</div>
                        </div>
                        <div class="timing-item">
                            <div class="timing-label"><i class="fas fa-bed"></i> Nap Time</div>
                            <div class="timing-value">{{ \Carbon\Carbon::parse($timings['nap_time'])->format('g:i A') }}</div>
                        </div>
                        <div class="timing-item">
                            <div class="timing-label"><i class="fas fa-cookie"></i> Snack</div>
                            <div class="timing-value">{{ \Carbon\Carbon::parse($timings['snack_time'])->format('g:i A') }}</div>
                        </div>
                        <div class="timing-item">
                            <div class="timing-label"><i class="fas fa-door-closed"></i> Facility Closes</div>
                            <div class="timing-value">{{ \Carbon\Carbon::parse($timings['closing_time'])->format('g:i A') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Classrooms -->
                <div class="sidebar-card">
                    <div class="schedule-sidebar-header">
                        <h3><i class="fas fa-chalkboard-teacher"></i> My Classrooms</h3>
                    </div>
                    @forelse($classrooms as $classroom)
                        <div class="classroom-box">
                            <div class="classroom-name">{{ $classroom->name }}</div>
                            <div class="classroom-detail">
                                <span>{{ $classroom->class }}</span>
                                <span><i class="fas fa-users"></i> {{ $classroom->capacity }}</span>
                            </div>
                        </div>
                    @empty
                        <p style="color: #6b7280; font-size: 14px; text-align: center;">No classrooms assigned.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            const link = e.target.closest('.btn-nav-week');
            if (link) {
                e.preventDefault();
                const url = link.href;
                
                // Add loading opacity
                const container = document.getElementById('schedule-content');
                if(container) {
                    container.style.opacity = '0.5';

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('schedule-content').innerHTML;
                            container.innerHTML = newContent;
                            container.style.opacity = '1';
                            
                            // IMPORTANT: Do NOT pushState to history (keeps URL same)
                        })
                        .catch(err => {
                            console.error('Failed to load schedule', err);
                            container.style.opacity = '1';
                        });
                }
            }
        });
    });
</script>
@endsection
