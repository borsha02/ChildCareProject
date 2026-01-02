@extends('layouts.caregiver')

@section('title', 'My Schedule')

@section('content')
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
        <div class="card" style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <button
                    style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-chevron-left"></i> Previous Week
                </button>
                <h3 style="color: #1f2937; font-size: 20px;">Week of December 23 - 29, 2025</h3>
                <button
                    style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer;">
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
@endsection
