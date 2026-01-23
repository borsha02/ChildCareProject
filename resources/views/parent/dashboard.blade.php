<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/parentdashboard.css'])
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
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>Parent Account</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('parent.dashboard') }}" class="nav-item active">
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
                <h1>Dashboard Overview</h1>
                <div class="top-bar-actions">
                    <!-- <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div> -->
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
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <a href="{{ route('parent.child-profile') }}" class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-child"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $childrenCount }}</h3>
                            <p>Registered Children</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $attendanceRate }}%</h3>
                            <p>Attendance Rate</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ number_format($pendingPayment, 2) }}</h3>
                            <p>Pending Payment</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.events') }}" class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $upcomingEventsCount }}</h3>
                            <p>Upcoming Events</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="stat-card">
                        <div class="stat-icon pink">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $caregiversCount }}</h3>
                            <p>Assigned Caregivers</p>
                        </div>
                    </a>
                    <a href="{{ route('parent.health') }}" class="stat-card">
                        <div class="stat-icon cyan">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $healthRecordsCount }}</h3>
                            <p>Health Records</p>
                        </div>
                    </a>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- My Children -->
                    <div class="card">
                        <div class="card-header">
                            <h3>My Children</h3>
                            <a href="{{ route('parent.child-profile') }}" class="view-all">View All</a>
                        </div>
                        <div class="children-list">
                            @forelse($children as $child)
                                <div class="child-item" onclick="window.location='{{ route('parent.child-profile') }}'">
                                    @php
                                        $initials = strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1));
                                        $bgStyle = $loop->iteration % 2 == 0 ? 'background: linear-gradient(135deg, #3b82f6, #2563eb);' : '';
                                        $age = \Carbon\Carbon::parse($child->dob)->age;
                                        $statusClass = in_array($child->status, ['approved', 'enrolled']) ? 'present' : 'absent';
                                        $statusLabel = ucfirst($child->status);
                                    @endphp
                                    <div class="child-avatar" style="{{ $bgStyle }}">{{ $initials }}</div>
                                    <div class="child-info">
                                        <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                                        <p>Age: {{ $age }} years • Class: {{ $child->class ?? 'N/A' }}</p>
                                    </div>
                                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                            @empty
                                <div class="child-item" style="justify-content: center; background: transparent; cursor: default;">
                                    <p style="color: var(--text-muted);">No children registered yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Upcoming Events</h3>
                            <a href="{{ route('parent.events') }}" class="view-all">View All</a>
                        </div>
                        <div class="event-list">
                            @forelse($upcomingEvents as $event)
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="day">{{ $event->start_time->format('d') }}</div>
                                    <div class="month">{{ $event->start_time->format('M') }}</div>
                                </div>
                                <div class="event-info">
                                    <h4>{{ $event->title }}</h4>
                                    <p>{{ $event->start_time->format('g:i A') }} - {{ $event->location }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="event-item" style="justify-content: center; border: none;">
                                <p style="color: var(--text-muted); text-align: center;">No upcoming events.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="content-grid">
                    <!-- Today's Activities -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Today's Activities</h3>
                            <a href="{{ route('parent.reports') }}" class="view-all">View Details</a>
                        </div>
                        <div class="activity-list">
                            @forelse($todaysActivities as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon {{ $activity['type'] }}">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h4>{{ $activity['title'] }}</h4>
                                        <p>{{ $activity['description'] }}</p>
                                    </div>
                                    <div class="activity-time">{{ $activity['time'] }}</div>
                                </div>
                            @empty
                                <div class="activity-item" style="justify-content: center; border: none;">
                                    <p style="color: var(--text-muted); text-align: center;">No activities recorded today yet.</p>
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
                            <a href="{{ route('parent.messages') }}" class="action-btn">
                                <i class="fas fa-comment-dots"></i>
                                <p>Send Message</p>
                            </a>
                            <a href="{{ route('parent.invoice') }}" class="action-btn">
                                <i class="fas fa-credit-card"></i>
                                <p>Pay Invoice</p>
                            </a>
                            <a href="{{ route('parent.reports') }}" class="action-btn">
                                <i class="fas fa-download"></i>
                                <p>Download Report</p>
                            </a>
                            <a href="{{ route('parent.health') }}" class="action-btn">
                                <i class="fas fa-notes-medical"></i>
                                <p>Health Records</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Apply theme from local storage
        const savedTheme = localStorage.getItem('theme') || 'auto';
        if (savedTheme === 'auto') {
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.body.classList.add('dark-mode');
            }
        } else if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
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
