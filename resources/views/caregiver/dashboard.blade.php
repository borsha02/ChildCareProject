@extends('layouts.caregiver')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Dashboard Overview</h1>
        <div class="top-bar-actions">
           <!-- <div class="search-box">
                <input type="text" placeholder="Search...">
                <i class="fas fa-search"></i>
            </div> -->
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
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card clickable-card" onclick="window.location.href='{{ route('caregiver.assigned') }}'">
                <div class="stat-icon green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->count() }}</h3>
                    <p>Assigned Children</p>
                </div>
            </div>
            <!-- Dynamic Status -->
            <div class="stat-card clickable-card" onclick="window.location.href='{{ route('caregiver.attendance') }}'">
                <div class="stat-icon blue">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $presentCount }}</h3>
                    <p>Present Today</p>
                </div>
            </div>
            <div class="stat-card clickable-card" onclick="window.location.href='{{ route('caregiver.daily-reports') }}'">
                <div class="stat-icon orange">
                    <i class="fas fa-tasks"></i>
                </div>
                <!-- Logic is handled in controller -->
                <div class="stat-details">
                    <h3>{{ $pendingReportsCount }}</h3>
                    <p>Pending Reports</p>
                </div>
            </div>
            <div class="stat-card clickable-card" onclick="window.location.href='{{ route('caregiver.messages') }}'">
                <div class="stat-icon purple">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-details">
                    <h3>0</h3>
                    <p>New Messages</p>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Assigned Children -->
            <div class="card">
                <div class="card-header">
                    <h3>My Assigned Children</h3>
                    <a href="{{ route('caregiver.assigned') }}" class="view-all">View All</a>
                </div>
                <div class="children-list">
                    @forelse($assignedChildren->take(4) as $child)
                    <div class="child-item">
                        <div class="child-avatar" style="background: {{ '#' . substr(md5($child->first_name . $child->last_name), 0, 6) }};">
                            {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                        </div>
                        <div class="child-info">
                            <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                            <p>Age: {{ \Carbon\Carbon::parse($child->dob)->age }} years • Class: {{ $child->class }}</p>
                        </div>
                        <span class="status-badge {{ $child->status }}">{{ ucfirst($child->status) }}</span>
                    </div>
                    @empty
                    <div class="empty-state-dashboard">
                        <p>No children assigned yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="card">
                <div class="card-header">
                    <h3>Today's Schedule</h3>
                    <a href="{{ route('caregiver.schedule') }}" class="view-all">View Full</a>
                </div>
                <div class="schedule-list">
                    <div class="schedule-item">
                        <div class="schedule-time">
                            <div class="time">9:00</div>
                            <div class="period">AM</div>
                        </div>
                        <div class="schedule-info">
                            <h4>Morning Circle Time</h4>
                            <p>Preschool A</p>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">
                            <div class="time">10:30</div>
                            <div class="period">AM</div>
                        </div>
                        <div class="schedule-info">
                            <h4>Art Activity</h4>
                            <p>All Classes</p>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">
                            <div class="time">12:00</div>
                            <div class="period">PM</div>
                        </div>
                        <div class="schedule-info">
                            <h4>Lunch Time</h4>
                            <p>Cafeteria</p>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">
                            <div class="time">2:00</div>
                            <div class="period">PM</div>
                        </div>
                        <div class="schedule-info">
                            <h4>Outdoor Play</h4>
                            <p>Playground</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="content-grid">
            <!-- Today's Tasks -->
            <div class="card">
                <div class="card-header">
                    <h3>Today's Tasks</h3>
                    <a href="{{ route('caregiver.daily-reports') }}" class="view-all">View All</a>
                </div>
                <div class="task-list">
                    @forelse($tasks as $task)
                    <div class="task-item clickable-card" onclick="window.location.href='{{ $task['link'] }}'">
                        <div class="task-checkbox"></div>
                        <div class="task-content">
                            <p>{{ $task['title'] }}</p>
                            <span>{{ $task['description'] }} • Due: {{ $task['due'] }}</span>
                        </div>
                        <span class="task-priority {{ $task['priority'] }}">{{ ucfirst($task['priority']) }}</span>
                    </div>
                    @empty
                    <div class="empty-state-dashboard">
                        <p>No pending tasks for today!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3>Quick Actions</h3>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('caregiver.attendance') }}" class="action-btn">
                        <i class="fas fa-calendar-check"></i>
                        <p>Mark Attendance</p>
                    </a>
                    <a href="{{ route('caregiver.daily-reports') }}" class="action-btn">
                        <i class="fas fa-file-alt"></i>
                        <p>Create Report</p>
                    </a>
                    <a href="{{ route('caregiver.messages') }}" class="action-btn">
                        <i class="fas fa-comment-dots"></i>
                        <p>Send Message</p>
                    </a>
                    <a href="{{ route('caregiver.health') }}" class="action-btn">
                        <i class="fas fa-notes-medical"></i>
                        <p>Health Update</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
