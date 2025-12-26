<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/staff.css'])
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
                    <a href="{{ route('admin.staff') }}" class="nav-item active">
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
                <h1>Staff Management</h1>
                <div class="top-bar-actions">
                    <button class="add-btn">
                        <i class="fas fa-plus"></i>
                        Add Staff Member
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Leave Requests Section -->
                <div class="leave-requests-section">
                    <div class="section-header">
                        <h2>Pending Leave Requests</h2>
                        <span class="pending-badge">3 Pending</span>
                    </div>
                    <div class="leave-requests-grid">
                        <!-- Leave Request 1 -->
                        <div class="leave-request-card">
                            <div class="leave-header">
                                <div class="leave-staff-info">
                                    <h3>Lisa Johnson</h3>
                                    <p>Senior Caregiver • Preschool A</p>
                                </div>
                                <span class="leave-type-badge sick">Sick Leave</span>
                            </div>
                            <div class="leave-details">
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Start Date</div>
                                    <div class="leave-detail-value">Dec 25, 2025</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">End Date</div>
                                    <div class="leave-detail-value">Dec 27, 2025</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Duration</div>
                                    <div class="leave-detail-value">3 Days</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Requested On</div>
                                    <div class="leave-detail-value">Dec 20, 2025</div>
                                </div>
                            </div>
                            <div class="leave-reason">
                                <strong>Reason:</strong> Experiencing flu symptoms and need time to recover. Doctor's note will be provided.
                            </div>
                            <div class="leave-actions">
                                <button class="approve-btn" onclick="approveLeave(1)">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="deny-btn" onclick="denyLeave(1)">
                                    <i class="fas fa-times"></i> Deny
                                </button>
                            </div>
                        </div>

                        <!-- Leave Request 2 -->
                        <div class="leave-request-card">
                            <div class="leave-header">
                                <div class="leave-staff-info">
                                    <h3>Mike Thompson</h3>
                                    <p>Caregiver • Toddler B</p>
                                </div>
                                <span class="leave-type-badge vacation">Vacation</span>
                            </div>
                            <div class="leave-details">
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Start Date</div>
                                    <div class="leave-detail-value">Jan 5, 2026</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">End Date</div>
                                    <div class="leave-detail-value">Jan 12, 2026</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Duration</div>
                                    <div class="leave-detail-value">7 Days</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Requested On</div>
                                    <div class="leave-detail-value">Dec 18, 2025</div>
                                </div>
                            </div>
                            <div class="leave-reason">
                                <strong>Reason:</strong> Family vacation planned for the new year. All responsibilities will be handed over before departure.
                            </div>
                            <div class="leave-actions">
                                <button class="approve-btn" onclick="approveLeave(2)">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="deny-btn" onclick="denyLeave(2)">
                                    <i class="fas fa-times"></i> Deny
                                </button>
                            </div>
                        </div>

                        <!-- Leave Request 3 -->
                        <div class="leave-request-card">
                            <div class="leave-header">
                                <div class="leave-staff-info">
                                    <h3>Emily Davis</h3>
                                    <p>Assistant Teacher • Kindergarten</p>
                                </div>
                                <span class="leave-type-badge personal">Personal</span>
                            </div>
                            <div class="leave-details">
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Start Date</div>
                                    <div class="leave-detail-value">Dec 28, 2025</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">End Date</div>
                                    <div class="leave-detail-value">Dec 28, 2025</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Duration</div>
                                    <div class="leave-detail-value">1 Day</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Requested On</div>
                                    <div class="leave-detail-value">Dec 21, 2025</div>
                                </div>
                            </div>
                            <div class="leave-reason">
                                <strong>Reason:</strong> Personal appointment that cannot be rescheduled. Will ensure all lesson plans are prepared in advance.
                            </div>
                            <div class="leave-actions">
                                <button class="approve-btn" onclick="approveLeave(3)">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="deny-btn" onclick="denyLeave(3)">
                                    <i class="fas fa-times"></i> Deny
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Grid -->
                <div class="staff-grid">
                    <!-- Staff Member 1 -->
                    <div class="staff-card">
                        <div class="staff-header">
                            <div class="staff-photo">LJ</div>
                            <div class="staff-info">
                                <h3>Lisa Johnson</h3>
                                <div class="staff-role">Senior Caregiver</div>
                                <div class="staff-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    5.0
                                </div>
                            </div>
                        </div>
                        <div class="staff-details">
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Preschool A</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Shift</div>
                                <div class="detail-value">8AM - 4PM</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Experience</div>
                                <div class="detail-value">5 Years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value">+1 234-567-8903</div>
                            </div>
                        </div>
                        <div class="staff-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Staff Member 2 -->
                    <div class="staff-card">
                        <div class="staff-header">
                            <div class="staff-photo" style="background: linear-gradient(135deg, #10b981, #059669);">MT</div>
                            <div class="staff-info">
                                <h3>Mike Thompson</h3>
                                <div class="staff-role">Caregiver</div>
                                <div class="staff-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    4.5
                                </div>
                            </div>
                        </div>
                        <div class="staff-details">
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Toddler B</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Shift</div>
                                <div class="detail-value">9AM - 5PM</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Experience</div>
                                <div class="detail-value">3 Years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value">+1 234-567-8907</div>
                            </div>
                        </div>
                        <div class="staff-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Staff Member 3 -->
                    <div class="staff-card">
                        <div class="staff-header">
                            <div class="staff-photo" style="background: linear-gradient(135deg, #f59e0b, #d97706);">ED</div>
                            <div class="staff-info">
                                <h3>Emily Davis</h3>
                                <div class="staff-role">Assistant Teacher</div>
                                <div class="staff-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    4.7
                                </div>
                            </div>
                        </div>
                        <div class="staff-details">
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Kindergarten</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Shift</div>
                                <div class="detail-value">7AM - 3PM</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Experience</div>
                                <div class="detail-value">4 Years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value">+1 234-567-8906</div>
                            </div>
                        </div>
                        <div class="staff-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function approveLeave(id) {
            if (confirm('Approve this leave request?')) {
                alert('Leave request approved successfully!');
                console.log('Approved leave:', id);
                // In real app, send to backend
            }
        }

        function denyLeave(id) {
            const reason = prompt('Please provide a reason for denial:');
            if (reason) {
                alert('Leave request denied.');
                console.log('Denied leave:', id, 'Reason:', reason);
                // In real app, send to backend
            }
        }

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
