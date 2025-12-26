<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Reports - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/reports.css'])
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
                    <a href="{{ route('admin.reports') }}" class="nav-item active">
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
                    <a href="{{ route('admin.announcements') }}" class="nav-item">
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
                    <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Daily Activity Reports</h1>
                <div class="top-bar-actions">
                    <input type="date" class="date-picker" value="{{ date('Y-m-d') }}">
                    <button class="export-btn">
                        <i class="fas fa-download"></i>
                        Export Reports
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <select class="filter-select" id="childFilter">
                        <option value="">All Children</option>
                        <option value="1">Emma Martinez</option>
                        <option value="2">Lucas Johnson</option>
                        <option value="3">Sophia Williams</option>
                    </select>
                    <select class="filter-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="meal">Meals</option>
                        <option value="nap">Naps</option>
                        <option value="health">Health</option>
                        <option value="activity">Activities</option>
                    </select>
                </div>

                <!-- Reports Grid -->
                <div class="reports-grid">
                    <!-- Meal Report -->
                    <div class="report-card meal" data-type="meal">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Lunch - Emma Martinez</h3>
                                <div class="report-meta">Today, 12:30 PM</div>
                            </div>
                            <div class="report-icon meal">
                                <i class="fas fa-utensils"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">Food Served</span>
                                <span class="report-value">Chicken, Rice, Vegetables</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Amount Eaten</span>
                                <span class="report-value">Most (75%)</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Appetite</span>
                                <span class="report-value">Good</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Ate well today. Enjoyed the chicken and asked for seconds on vegetables.
                        </div>
                        <div class="report-footer">
                            <span class="reporter-info">Reported by: Sarah (Caregiver)</span>
                            <button class="view-btn">View Details</button>
                        </div>
                    </div>

                    <!-- Nap Report -->
                    <div class="report-card nap" data-type="nap">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Afternoon Nap - Lucas Johnson</h3>
                                <div class="report-meta">Today, 2:00 PM</div>
                            </div>
                            <div class="report-icon nap">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">Start Time</span>
                                <span class="report-value">1:15 PM</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">End Time</span>
                                <span class="report-value">3:00 PM</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Duration</span>
                                <span class="report-value">1 hour 45 minutes</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Fell asleep quickly and had a peaceful nap. Woke up in good mood.
                        </div>
                        <div class="report-footer">
                            <span class="reporter-info">Reported by: Mike (Caregiver)</span>
                            <button class="view-btn">View Details</button>
                        </div>
                    </div>

                    <!-- Health Report -->
                    <div class="report-card health" data-type="health">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Health Check - Sophia Williams</h3>
                                <div class="report-meta">Today, 10:00 AM</div>
                            </div>
                            <div class="report-icon health">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">Temperature</span>
                                <span class="report-value">98.6°F (Normal)</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Condition</span>
                                <span class="report-value">Healthy</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Diaper Changes</span>
                                <span class="report-value">3 times</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Child is healthy and active. No concerns noted.
                        </div>
                        <div class="report-footer">
                            <span class="reporter-info">Reported by: Emily (Nurse)</span>
                            <button class="view-btn">View Details</button>
                        </div>
                    </div>

                    <!-- Activity Report -->
                    <div class="report-card activity" data-type="activity">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Art Class - Emma Martinez</h3>
                                <div class="report-meta">Today, 11:00 AM</div>
                            </div>
                            <div class="report-icon activity">
                                <i class="fas fa-palette"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">Activity</span>
                                <span class="report-value">Finger Painting</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Duration</span>
                                <span class="report-value">45 minutes</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Participation</span>
                                <span class="report-value">Excellent</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Very engaged in art activity. Created a beautiful painting of flowers.
                        </div>
                        <div class="report-footer">
                            <span class="reporter-info">Reported by: Lisa (Teacher)</span>
                            <button class="view-btn">View Details</button>
                        </div>
                    </div>

                    <!-- More Meal Report -->
                    <div class="report-card meal" data-type="meal">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Snack Time - Lucas Johnson</h3>
                                <div class="report-meta">Today, 3:30 PM</div>
                            </div>
                            <div class="report-icon meal">
                                <i class="fas fa-cookie-bite"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">Food Served</span>
                                <span class="report-value">Apple slices, Crackers, Juice</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Amount Eaten</span>
                                <span class="report-value">All (100%)</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Appetite</span>
                                <span class="report-value">Excellent</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Finished all snacks. Particularly enjoyed the apple slices.
                        </div>
                        <div class="report-footer">
                            <span class="reporter-info">Reported by: Sarah (Caregiver)</span>
                            <button class="view-btn">View Details</button>
                        </div>
                    </div>

                    <!-- Activity Report 2 -->
                    <div class="report-card activity" data-type="activity">
                        <div class="report-header">
                            <div class="report-title">
                                <h3>Outdoor Play - Sophia Williams</h3>
                                <div class="report-meta">Today, 4:00 PM</div>
                            </div>
                            <div class="report-icon activity">
                                <i class="fas fa-running"></i>
                            </div>
                        </div>
                        <div class="report-content">
                            <div class="report-item">
                                <span class="report-label">Activity</span>
                                <span class="report-value">Playground Time</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Duration</span>
                                <span class="report-value">30 minutes</span>
                            </div>
                            <div class="report-item">
                                <span class="report-label">Participation</span>
                                <span class="report-value">Very Active</span>
                            </div>
                        </div>
                        <div class="report-notes">
                            Played on swings and slides. Interacted well with other children.
                        </div>
                        <div class="report-footer">
                            <span class="reporter-info">Reported by: Mike (Caregiver)</span>
                            <button class="view-btn">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Filter by type
        document.getElementById('typeFilter').addEventListener('change', function(e) {
            const type = e.target.value;
            const cards = document.querySelectorAll('.report-card');
            cards.forEach(card => {
                card.style.display = (!type || card.dataset.type === type) ? '' : 'none';
            });
        });

        // Mobile toggle
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
