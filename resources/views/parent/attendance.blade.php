<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/attendance.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Little Stars Childcare</h2>
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
                    <a href="{{ route('parent.attendance') }}" class="nav-item active">
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
                    <a href="{{ route('parent.events') }}" class="nav-item">
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
                    <h1>Attendance Tracking</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
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
                <div class="attendance-container">

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $attendanceRate }}%</h3>
                    <p>Attendance Rate</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $daysPresent }}</h3>
                    <p>Days Present</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $daysAbsent }}</h3>
                    <p>Days Absent</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $timesLate }}</h3>
                    <p>Times Late</p>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Calendar Card -->
            <div class="card">
                <div class="card-header">
                    <h2>Monthly Calendar</h2>
                    <div class="month-selector">
                        <button class="month-btn" onclick="previousMonth()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="current-month" id="currentMonth">December 2025</span>
                        <button class="month-btn" onclick="nextMonth()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="calendar" id="calendar">
                    <!-- Calendar Headers -->
                    <div class="calendar-day header">Sun</div>
                    <div class="calendar-day header">Mon</div>
                    <div class="calendar-day header">Tue</div>
                    <div class="calendar-day header">Wed</div>
                    <div class="calendar-day header">Thu</div>
                    <div class="calendar-day header">Fri</div>
                    <div class="calendar-day header">Sat</div>

                    <!-- Calendar Days (Dynamic) -->
                    @php
                        $today = \Carbon\Carbon::now();
                        // Empty cells before first day
                        for ($i = 0; $i < $startDayOfWeek; $i++) {
                            echo '<div class="calendar-day empty"></div>';
                        }
                        
                        // Days of the month
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $currentDate = \Carbon\Carbon::create($year, $month, $day);
                            $isToday = $currentDate->isToday();
                            $isFuture = $currentDate->isFuture();
                            
                            // Get attendance for this day
                            $dayAttendance = $calendarData[$day] ?? [];
                            $status = 'future';
                            $statusText = '';
                            
                            if (!$isFuture && count($dayAttendance) > 0) {
                                // If multiple children, show the most common status
                                $statuses = collect($dayAttendance)->pluck('status');
                                $status = $statuses->first(); // or use mode/most common
                                $statusText = ucfirst($status);
                            } elseif (!$isFuture) {
                                $status = 'absent';
                                $statusText = 'No Record';
                            }
                            
                            $classes = "calendar-day $status";
                            if ($isToday) $classes .= ' today';
                            
                            echo "<div class='$classes'>";
                            echo "<span class='day-number'>$day</span>";
                            if ($statusText) {
                                echo "<span class='day-status'>$statusText</span>";
                            }
                            echo '</div>';
                        }
                    @endphp
                </div>

                <!-- Legend -->
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color present"></div>
                        <span>Present</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color absent"></div>
                        <span>Absent</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color future"></div>
                        <span>Future</span>
                    </div>
                </div>
            </div>

            <!-- Attendance Summary Card -->
            <div class="card">
                <div class="card-header">
                    <h2>Quick Summary</h2>
                </div>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; color: white;">
                        <div style="font-size: 48px; font-weight: bold; margin-bottom: 8px;">{{ $attendanceRate }}%</div>
                        <div style="font-size: 14px; opacity: 0.9;">Overall Attendance Rate</div>
                    </div>

                    <div style="display: grid; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Total Days:</span>
                            <span style="color: #1f2937; font-weight: 600;">{{ $totalDays }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Present:</span>
                            <span style="color: #059669; font-weight: 600;">{{ $daysPresent }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Absent:</span>
                            <span style="color: #dc2626; font-weight: 600;">{{ $daysAbsent }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">Late Arrivals:</span>
                            <span style="color: #d97706; font-weight: 600;">{{ $timesLate }}</span>
                        </div>
                    </div>

                    <div style="padding: 16px; background: #fef3c7; border-radius: 10px; border-left: 4px solid #f59e0b;">
                        <div style="font-size: 13px; color: #92400e; font-weight: 600; margin-bottom: 4px;">
                            <i class="fas fa-info-circle"></i> Note
                        </div>
                        <div style="font-size: 12px; color: #78350f;">
                            Excellent attendance! Keep up the good work.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card">
            <div class="card-header">
                <h2>Recent Attendance History</h2>
            </div>

            <!-- Child Filter -->
            <div class="child-filter">
                <button onclick="window.location.href='{{ route('parent.attendance', array_merge(request()->query(), ['child_id' => 'all'])) }}'" 
                        class="filter-btn {{ request('child_id') == 'all' || !request('child_id') ? 'active' : '' }}">
                    All Children
                </button>
                @foreach($children as $child)
                    <button onclick="window.location.href='{{ route('parent.attendance', array_merge(request()->query(), ['child_id' => $child->id])) }}'" 
                            class="filter-btn {{ request('child_id') == $child->id ? 'active' : '' }}">
                        {{ $child->first_name }} {{ $child->last_name }}
                    </button>
                @endforeach
            </div>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Child</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Marked By</th> <!-- New Column -->
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendance as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                            <td>
                                <div class="child-name">
                                    <div class="child-avatar-small" style="background: linear-gradient(135deg, {{ $loop->iteration % 2 == 0 ? '#3b82f6, #2563eb' : '#10b981, #059669' }});">
                                        {{ strtoupper(substr($attendance->child->first_name, 0, 1) . substr($attendance->child->last_name, 0, 1)) }}
                                    </div>
                                    <span>{{ $attendance->child->first_name }} {{ $attendance->child->last_name }}</span>
                                </div>
                            </td>
                            <td><span class="status-badge {{ $attendance->status }}">{{ ucfirst($attendance->status) }}</span></td>
                            <td><span class="time-in">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('g:i A') : '-' }}</span></td>
                            <td><span class="time-out">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('g:i A') : '-' }}</span></td>
                            <td>
                                <!-- Display who marked the attendance -->
                                <span class="marked-by" style="font-size: 0.9em; color: #555;">
                                    <i class="fas fa-user-edit" style="margin-right: 4px; color: #888;"></i>
                                    {{ ($attendance->caregiver && $attendance->caregiver->role === 'admin') ? 'Admin' : ($attendance->caregiver->name ?? 'System') }}
                                </span>
                            </td>
                            <td>{{ $attendance->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Month navigation
        let currentMonth = new Date();

        function previousMonth() {
            currentMonth.setMonth(currentMonth.getMonth() - 1);
            updateMonthDisplay();
        }

        function nextMonth() {
            currentMonth.setMonth(currentMonth.getMonth() + 1);
            updateMonthDisplay();
        }

        function updateMonthDisplay() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            document.getElementById('currentMonth').textContent =
                monthNames[currentMonth.getMonth()] + ' ' + currentMonth.getFullYear();
        }



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
