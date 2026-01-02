<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/reports.css'])
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
                    <a href="{{ route('parent.reports') }}" class="nav-item active">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('parent.messages') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <span class="badge">3</span>
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
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
                    <h1>Progress Reports</h1>
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
                <div class="reports-container">
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-group">
                            <label>Select Child:</label>
                            <select class="filter-select" id="childFilter">
                                <option value="all">All Children</option>
                                <option value="emma">Emma Doe</option>
                                <option value="lucas">Lucas James</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Report Type:</label>
                            <select class="filter-select" id="reportType">
                                <option value="all">All Reports</option>
                                <option value="progress">Progress Reports</option>
                                <option value="activity">Activity Reports</option>
                                <option value="behavior">Behavior Reports</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Time Period:</label>
                            <select class="filter-select" id="timePeriod">
                                <option value="month">This Month</option>
                                <option value="quarter">This Quarter</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                    </div>

                    <!-- Stats Overview -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Excellent</h3>
                                <p>Overall Performance</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-details">
                                <h3>12</h3>
                                <p>Achievements</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="stat-details">
                                <h3>8/10</h3>
                                <p>Skills Mastered</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-smile"></i>
                            </div>
                            <div class="stat-details">
                                <h3>95%</h3>
                                <p>Positive Behavior</p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Cards Grid -->
                    <div class="content-grid">
                        <!-- Progress Report Card -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-chart-line"></i> Progress Report</h2>
                                <button class="download-btn">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                            <div class="report-content">
                                <div class="child-selector">
                                    <div class="child-tab active" data-child="emma">
                                        <div class="child-avatar-small">EM</div>
                                        <span>Emma</span>
                                    </div>
                                    <div class="child-tab" data-child="lucas">
                                        <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                        <span>Lucas</span>
                                    </div>
                                </div>

                                <div class="progress-section">
                                    <h3>Development Areas</h3>
                                    <div class="progress-item">
                                        <div class="progress-header">
                                            <span>Cognitive Skills</span>
                                            <span class="progress-value">85%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 85%; background: linear-gradient(90deg, #3b82f6, #2563eb);"></div>
                                        </div>
                                    </div>
                                    <div class="progress-item">
                                        <div class="progress-header">
                                            <span>Social Skills</span>
                                            <span class="progress-value">92%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 92%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                                        </div>
                                    </div>
                                    <div class="progress-item">
                                        <div class="progress-header">
                                            <span>Motor Skills</span>
                                            <span class="progress-value">78%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 78%; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                                        </div>
                                    </div>
                                    <div class="progress-item">
                                        <div class="progress-header">
                                            <span>Language Skills</span>
                                            <span class="progress-value">88%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 88%; background: linear-gradient(90deg, #8b5cf6, #7c3aed);"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="teacher-notes">
                                    <h4><i class="fas fa-comment-dots"></i> Teacher's Notes</h4>
                                    <p>Emma has shown remarkable improvement in her social interactions and communication skills. She actively participates in group activities and demonstrates excellent problem-solving abilities.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Summary -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-clipboard-list"></i> Activity Summary</h2>
                                <span class="date-range">Dec 1-22, 2025</span>
                            </div>
                            <div class="activity-summary">
                                <div class="activity-stat">
                                    <div class="activity-icon art">
                                        <i class="fas fa-palette"></i>
                                    </div>
                                    <div class="activity-info">
                                        <h4>Art & Crafts</h4>
                                        <p>15 sessions completed</p>
                                        <div class="activity-rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-stat">
                                    <div class="activity-icon music">
                                        <i class="fas fa-music"></i>
                                    </div>
                                    <div class="activity-info">
                                        <h4>Music & Dance</h4>
                                        <p>12 sessions completed</p>
                                        <div class="activity-rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-stat">
                                    <div class="activity-icon sports">
                                        <i class="fas fa-running"></i>
                                    </div>
                                    <div class="activity-info">
                                        <h4>Physical Activities</h4>
                                        <p>20 sessions completed</p>
                                        <div class="activity-rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-stat">
                                    <div class="activity-icon reading">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="activity-info">
                                        <h4>Reading & Stories</h4>
                                        <p>18 sessions completed</p>
                                        <div class="activity-rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports Table -->
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-file-alt"></i> Recent Reports</h2>
                            <button class="view-all-btn">View All</button>
                        </div>
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Child</th>
                                    <th>Report Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dec 20, 2025</td>
                                    <td>
                                        <div class="child-name">
                                            <div class="child-avatar-small">EM</div>
                                            <span>Emma Doe</span>
                                        </div>
                                    </td>
                                    <td><span class="report-type progress">Progress Report</span></td>
                                    <td><span class="status-badge completed">Completed</span></td>
                                    <td>
                                        <button class="action-icon-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dec 18, 2025</td>
                                    <td>
                                        <div class="child-name">
                                            <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                            <span>Lucas James</span>
                                        </div>
                                    </td>
                                    <td><span class="report-type activity">Activity Report</span></td>
                                    <td><span class="status-badge completed">Completed</span></td>
                                    <td>
                                        <button class="action-icon-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dec 15, 2025</td>
                                    <td>
                                        <div class="child-name">
                                            <div class="child-avatar-small">EM</div>
                                            <span>Emma Doe</span>
                                        </div>
                                    </td>
                                    <td><span class="report-type behavior">Behavior Report</span></td>
                                    <td><span class="status-badge completed">Completed</span></td>
                                    <td>
                                        <button class="action-icon-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dec 10, 2025</td>
                                    <td>
                                        <div class="child-name">
                                            <div class="child-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                                            <span>Lucas James</span>
                                        </div>
                                    </td>
                                    <td><span class="report-type progress">Progress Report</span></td>
                                    <td><span class="status-badge pending">Pending Review</span></td>
                                    <td>
                                        <button class="action-icon-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-icon-btn download" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Child tab switching
        document.querySelectorAll('.child-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.child-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                // Here you would load the specific child's data
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
