<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/announcements.css'])
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <h2>Admin Panel</h2>
                </div>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div class="user-details">
                        <h4>Administrator</h4>
                        <p>System Admin</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="{{ route('admin.users') }}" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="{{ route('admin.children') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Records</span>
                    </a>
                    <a href="{{ route('admin.staff') }}" class="nav-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Staff Management</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Operations</div>
                    <a href="{{ route('admin.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('admin.invoices') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('admin.payments.pending') }}" class="nav-item">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Approvals</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('admin.announcements') }}" class="nav-item active">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                    <a href="{{ route('admin.communication') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Communication Logs</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('admin.backup') }}" class="nav-item">
                        <i class="fas fa-database"></i>
                        <span>Backup & Restore</span>
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
                <h1>Announcements</h1>
                <div class="top-bar-actions">
                    <button class="create-btn" onclick="toggleForm()">
                        <i class="fas fa-plus"></i>
                        Create Announcement
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Announcement Form -->
                <div class="announcement-form" id="announcementForm">
                    <div class="form-header">
                        <h2>Create New Announcement</h2>
                        <button class="close-form" onclick="toggleForm()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.announcements.create') }}" method="POST">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="title">Announcement Title *</label>
                                <input type="text" id="title" name="title" required placeholder="Enter announcement title">
                            </div>
                            <div class="form-group">
                                <label for="target_audience">Target Audience *</label>
                                <select id="target_audience" name="target_audience" required>
                                    <option value="">Select audience</option>
                                    <option value="all">All Users</option>
                                    <option value="parents">Parents Only</option>
                                    <option value="staff">Staff Only</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority Level *</label>
                                <select id="priority" name="priority" required>
                                    <option value="">Select priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" required placeholder="Enter your announcement message..."></textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="toggleForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Send Announcement
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Announcements List -->
                <div class="announcements-list">
                    @forelse($announcements ?? [] as $announcement)
                    <div class="announcement-card {{ $announcement['priority'] ?? 'low' }}">
                        <div class="announcement-header">
                            <div class="announcement-title">
                                <h3>{{ $announcement['title'] ?? 'Announcement Title' }}</h3>
                                <div class="announcement-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $announcement['date'] ?? 'Today' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $announcement['author'] ?? 'Administrator' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-badges">
                                <span class="priority-badge {{ $announcement['priority'] ?? 'low' }}">
                                    {{ ucfirst($announcement['priority'] ?? 'Low') }} Priority
                                </span>
                                <span class="audience-badge">
                                    {{ ucfirst($announcement['target_audience'] ?? 'All') }}
                                </span>
                            </div>
                        </div>
                        <div class="announcement-content">
                            {{ $announcement['message'] ?? 'Announcement message will appear here.' }}
                        </div>
                        <div class="announcement-actions">
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="confirmDelete({{ $announcement['id'] ?? 0 }})">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                    @empty
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-bullhorn"></i>
                        <h3>No Announcements Yet</h3>
                        <p>Create your first announcement to notify parents and staff about important updates.</p>
                        <button class="create-btn" onclick="toggleForm()">
                            <i class="fas fa-plus"></i>
                            Create First Announcement
                        </button>
                    </div>
                    @endforelse

                    <!-- Sample Announcements (for demonstration) -->
                    @if(empty($announcements))
                    <div class="announcement-card high">
                        <div class="announcement-header">
                            <div class="announcement-title">
                                <h3>Holiday Closure Notice</h3>
                                <div class="announcement-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Dec 20, 2025</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>Administrator</span>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-badges">
                                <span class="priority-badge high">High Priority</span>
                                <span class="audience-badge">All Users</span>
                            </div>
                        </div>
                        <div class="announcement-content">
                            The childcare center will be closed from December 24th to December 26th for the Christmas holiday. We will resume normal operations on December 27th. We wish all families a wonderful holiday season!
                        </div>
                        <div class="announcement-actions">
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="confirmDelete(1)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="announcement-card medium">
                        <div class="announcement-header">
                            <div class="announcement-title">
                                <h3>Parent-Teacher Conference</h3>
                                <div class="announcement-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Dec 18, 2025</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>Administrator</span>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-badges">
                                <span class="priority-badge medium">Medium Priority</span>
                                <span class="audience-badge">Parents</span>
                            </div>
                        </div>
                        <div class="announcement-content">
                            Parent-teacher conferences are scheduled for next week. Please check your email for your assigned time slot. If you need to reschedule, contact the office by Friday.
                        </div>
                        <div class="announcement-actions">
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="confirmDelete(2)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="announcement-card low">
                        <div class="announcement-header">
                            <div class="announcement-title">
                                <h3>Staff Training Session</h3>
                                <div class="announcement-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Dec 15, 2025</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>Administrator</span>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-badges">
                                <span class="priority-badge low">Low Priority</span>
                                <span class="audience-badge">Staff</span>
                            </div>
                        </div>
                        <div class="announcement-content">
                            Reminder: All staff members are required to attend the CPR and First Aid training session on Saturday, December 21st at 9:00 AM. Please confirm your attendance.
                        </div>
                        <div class="announcement-actions">
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="confirmDelete(3)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle announcement form
        function toggleForm() {
            const form = document.getElementById('announcementForm');
            form.classList.toggle('active');

            // Scroll to form if opening
            if (form.classList.contains('active')) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
                // In a real application, this would submit a delete request
                console.log('Deleting announcement:', id);
                alert('Announcement deleted successfully!');
            }
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
