<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/notifications.css'])
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
                    <a href="{{ route('parent.notifications') }}" class="nav-item active">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge" id="sidebar-badge">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
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
                    <h1>Notifications</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search notifications...">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('parent.notifications') }}" class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
                    </a>
                    <a href="{{ route('parent.messages') }}" class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <div class="notifications-container">
                    <!-- Notification Actions -->
                    <div class="notification-actions">
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">All</button>
                            <button class="filter-tab" data-filter="unread">Unread (<span id="tab-unread-count">{{ $unreadCount }}</span>)</button>
                            <button class="filter-tab" data-filter="important">Important</button>
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn mark-all">
                                <i class="fas fa-check-double"></i> Mark all as read
                            </button>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="notifications-list">
                        @forelse($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                                $iconClass = 'fas fa-bell';
                                $bgClass = 'message'; // Default background class

                                if(isset($data['type'])) {
                                    switch($data['type']) {
                                        case 'child_approved':
                                            $iconClass = 'fas fa-child';
                                            $bgClass = 'achievement';
                                            break;
                                        case 'payment':
                                            $iconClass = 'fas fa-dollar-sign';
                                            $bgClass = 'payment';
                                            break;
                                        case 'event':
                                            $iconClass = 'fas fa-calendar';
                                            $bgClass = 'event';
                                            break;
                                        // Add more cases as needed
                                    }
                                }
                            @endphp
                            <div class="notification-item {{ $isUnread ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                                <div class="notification-icon {{ $bgClass }}">
                                    <i class="{{ $iconClass }}"></i>
                                </div>
                                <div class="notification-content">
                                    <h4>{{ isset($data['type']) && $data['type'] == 'child_approved' ? 'Registration Approved' : 'New Notification' }}</h4>
                                    <p>{{ $data['message'] ?? 'No message content' }}</p>
                                    <div class="notification-meta">
                                        <span class="time"><i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                        <span class="category">System</span>
                                    </div>
                                </div>
                                <div class="notification-actions-menu">
                                    @if($isUnread)
                                        <button class="mark-read-btn" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="delete-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="notification-item" style="justify-content: center; background: transparent; cursor: default;">
                                <div class="notification-content" style="text-align: center;">
                                    <p style="color: var(--text-muted); margin: 0;">You have no notifications at this time.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Update badge counts
        function updateBadges(count) {
            const sidebarBadge = document.getElementById('sidebar-badge');
            const tabUnreadCount = document.getElementById('tab-unread-count');
            const topBarDot = document.querySelector('.notification-dot');

            sidebarBadge.textContent = count > 0 ? count : '';
            tabUnreadCount.textContent = count;
            
            if (count > 0) {
                if (topBarDot) topBarDot.style.display = 'block';
            } else {
                if (topBarDot) topBarDot.style.display = 'none';
            }
        }

        function decrementUnreadCount() {
            const tabUnreadCount = document.getElementById('tab-unread-count');
            let current = parseInt(tabUnreadCount.textContent) || 0;
            if (current > 0) {
                updateBadges(current - 1);
            }
        }

        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const notifications = document.querySelectorAll('.notification-item');

                notifications.forEach(notif => {
                    if (filter === 'all') {
                        notif.style.display = 'flex';
                    } else if (filter === 'unread') {
                        notif.style.display = notif.classList.contains('unread') ? 'flex' : 'none';
                    } else if (filter === 'important') {
                        notif.style.display = notif.classList.contains('important') ? 'flex' : 'none';
                    }
                });
            });
        });

        // Mark all as read
        document.querySelector('.mark-all').addEventListener('click', () => {
             fetch("{{ route('parent.notifications.mark-all') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const markBtn = item.querySelector('.mark-read-btn');
                        if (markBtn) markBtn.remove();
                    });
                    updateBadges(0);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Mark individual as read
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const notificationItem = this.closest('.notification-item');
                const id = notificationItem.dataset.id;
                
                fetch(`/parent/notifications/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationItem.classList.remove('unread');
                        this.remove();
                        decrementUnreadCount();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Delete notification
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const notificationItem = this.closest('.notification-item');
                const id = notificationItem.dataset.id;

                if (!confirm('Are you sure you want to delete this notification?')) return;

                fetch(`/parent/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (notificationItem.classList.contains('unread')) {
                            decrementUnreadCount();
                        }
                        notificationItem.style.animation = 'slideOut 0.3s ease';
                        setTimeout(() => notificationItem.remove(), 300);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
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
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
