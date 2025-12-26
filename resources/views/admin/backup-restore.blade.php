<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Restore - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/settings.css'])
</head>
<body>
    <div class="dashboard-container">
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
                    <a href="{{ route('admin.backup') }}" class="nav-item active">
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

        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Backup & Restore</h1>
            </div>

            <div class="content-area">
                <div class="tabs-container">
                    <div class="tab-content active">
                        <h2 style="margin-bottom: 20px;">Database Backup</h2>
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label>Create New Backup</label>
                                <button class="save-btn" onclick="createBackup()">
                                    <i class="fas fa-download"></i>
                                    Create Backup Now
                                </button>
                                <span class="setting-description">Download a complete backup of the database</span>
                            </div>
                            <div class="setting-group">
                                <label>Restore from Backup</label>
                                <input type="file" accept=".sql,.zip" style="padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                                <button class="save-btn" style="background: #f59e0b; margin-top: 10px;" onclick="restoreBackup()">
                                    <i class="fas fa-upload"></i>
                                    Restore Backup
                                </button>
                                <span class="setting-description">Upload and restore a previous backup</span>
                            </div>
                        </div>

                        <h2 style="margin: 40px 0 20px;">Backup History</h2>
                        <div class="classroom-list">
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>backup_2025_12_23_10_30.sql</h4>
                                    <p>Size: 2.5 MB • Created: Dec 23, 2025 10:30 AM</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>backup_2025_12_20_09_15.sql</h4>
                                    <p>Size: 2.3 MB • Created: Dec 20, 2025 09:15 AM</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>backup_2025_12_15_08_00.sql</h4>
                                    <p>Size: 2.1 MB • Created: Dec 15, 2025 08:00 AM</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function createBackup() {
            if (confirm('Create a new database backup?')) {
                alert('Backup created successfully! Download will start shortly.');
                console.log('Creating backup...');
            }
        }

        function restoreBackup() {
            if (confirm('WARNING: This will overwrite the current database. Are you sure?')) {
                alert('Backup restored successfully!');
                console.log('Restoring backup...');
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
