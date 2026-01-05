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
        <!-- Week Navigation -->
        <div class="card week-navigation-container">
            <div class="week-navigation-content">
                <button class="btn-nav-week">
                    <i class="fas fa-chevron-left"></i> Previous Week
                </button>
                <h3 class="week-title">Week of December 23 - 29, 2025</h3>
                <button class="btn-nav-week">
                    Next Week <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Monday -->
        <div class="card schedule-card">
            <div class="card-header">
                <h3>Monday, December 23</h3>
                <span class="label-today">Today</span>
            </div>
            <div class="schedule-list">
                <div class="schedule-item">
                    <div class="schedule-time">
                        <div class="time-text">8:00</div>
                        <div class="period-text">AM</div>
                    </div>
                    <div class="schedule-info">
                        <h4>Morning Arrival & Check-in</h4>
                        <p>Welcome children and parents • Preschool A</p>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-time">
                        <div class="time-text">9:00</div>
                        <div class="period-text">AM</div>
                    </div>
                    <div class="schedule-info">
                        <h4>Morning Circle Time</h4>
                        <p>Songs, stories, and calendar • Preschool A</p>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-time">
                        <div class="time-text">10:00</div>
                        <div class="period-text">AM</div>
                    </div>
                    <div class="schedule-info">
                        <h4>Snack Time</h4>
                        <p>Healthy snacks and drinks • All Classes</p>
                    </div>
                </div>
                <!-- ... other items ... -->
                <div class="schedule-item">
                    <div class="schedule-time">
                        <div class="time-text">4:00</div>
                        <div class="period-text">PM</div>
                    </div>
                    <div class="schedule-info">
                        <h4>Free Play & Pick-up</h4>
                        <p>Indoor activities until parent arrival • All Classes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tuesday -->
        <div class="card schedule-card">
            <div class="card-header">
                <h3>Tuesday, December 24</h3>
            </div>
            <div class="schedule-list">
                <div class="schedule-item">
                    <div class="schedule-time">
                        <div class="time-text">8:00</div>
                        <div class="period-text">AM</div>
                    </div>
                    <div class="schedule-info">
                        <h4>Morning Arrival & Check-in</h4>
                        <p>Welcome children and parents • Preschool A</p>
                    </div>
                </div>
                <!-- Other items... -->
            </div>
        </div>

        <!-- Wednesday -->
        <div class="card schedule-card">
            <div class="card-header">
                <h3>Wednesday, December 25</h3>
                <span class="label-holiday">Holiday - Closed</span>
            </div>
            <div class="holiday-state">
                <i class="fas fa-calendar-times holiday-icon"></i>
                <p class="holiday-text">Facility closed for Christmas Day</p>
            </div>
        </div>
    </div>
@endsection
