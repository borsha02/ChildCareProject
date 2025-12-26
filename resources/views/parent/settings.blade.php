<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/settings.css'])
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
                        <h4>{{ Auth::user()->name }}</h4>
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
                        <span class="badge">3</span>
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">5</span>
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
                    <a href="{{ route('parent.settings') }}" class="nav-item active">
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
                    <h1>Settings</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search settings...">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('parent.notifications') }}" class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </a>
                    <a href="{{ route('parent.messages') }}" class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <div class="settings-container">
                    <!-- Settings Navigation -->
                    <div class="settings-nav">
                        <button class="settings-nav-item active" data-tab="profile">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </button>
                        <button class="settings-nav-item" data-tab="security">
                            <i class="fas fa-lock"></i>
                            <span>Security</span>
                        </button>
                        <button class="settings-nav-item" data-tab="notifications">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </button>
                        <button class="settings-nav-item" data-tab="privacy">
                            <i class="fas fa-shield-alt"></i>
                            <span>Privacy</span>
                        </button>
                        <button class="settings-nav-item" data-tab="preferences">
                            <i class="fas fa-sliders-h"></i>
                            <span>Preferences</span>
                        </button>
                    </div>

                    <!-- Settings Content -->
                    <div class="settings-content">
                        <!-- Profile Tab -->
                        <div class="settings-tab active" id="profile-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-user-circle"></i> Profile Information</h2>
                                </div>
                                <div class="profile-section">
                                    <div class="profile-avatar-section">
                                        <div class="profile-header-info">
                                            <h3>{{ Auth::user()->name }}</h3>
                                            <p>Parent Account</p>
                                            <p>{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    <form class="settings-form" action="{{ route('parent.settings.update') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-input" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" value="{{ Auth::user()->email }}" class="form-input" disabled style="background-color: #f3f4f6; cursor: not-allowed;">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input type="tel" name="phone" value="{{ Auth::user()->phone }}" class="form-input">
                                            </div>
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="date" name="dob" value="{{ Auth::user()->dob }}" class="form-input">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" value="{{ Auth::user()->address }}" class="form-input">
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="btn-cancel">Cancel</button>
                                            <button type="submit" class="btn-save">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="settings-tab" id="security-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-key"></i> Change Password</h2>
                                </div>
                                <form class="settings-form" action="{{ route('parent.settings.password') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <input type="password" name="current_password" class="form-input @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                                        @error('current_password')
                                            <span class="text-danger" style="color: red; font-size: 0.875em;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password" name="new_password" class="form-input @error('new_password') is-invalid @enderror" placeholder="Enter new password" required>
                                        @error('new_password')
                                            <span class="text-danger" style="color: red; font-size: 0.875em;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" class="form-input" placeholder="Confirm new password" required>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-save">Update Password</button>
                                    </div>
                                </form>
                            </div>


                        </div>

                        <!-- Notifications Tab -->
                        <div class="settings-tab" id="notifications-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-envelope"></i> Email Notifications</h2>
                                </div>
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <h4>Messages</h4>
                                        <p>Receive email notifications for new messages</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <h4>Events & Activities</h4>
                                        <p>Get notified about upcoming events and activities</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <h4>Payment Reminders</h4>
                                        <p>Receive reminders for upcoming payments</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <h4>Progress Reports</h4>
                                        <p>Get notified when new progress reports are available</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Tab -->
                        <div class="settings-tab" id="privacy-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-eye"></i> Profile Visibility</h2>
                                </div>
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <h4>Show Profile to Other Parents</h4>
                                        <p>Allow other parents to see your profile information</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <h4>Show Contact Information</h4>
                                        <p>Display your phone number and email to teachers</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-database"></i> Data & Privacy</h2>
                                </div>
                                <div class="privacy-actions">
                                    <button class="privacy-btn">
                                        <i class="fas fa-download"></i>
                                        <div>
                                            <h4>Download Your Data</h4>
                                            <p>Get a copy of your information</p>
                                        </div>
                                    </button>
                                    <button class="privacy-btn danger">
                                        <i class="fas fa-trash-alt"></i>
                                        <div>
                                            <h4>Delete Account</h4>
                                            <p>Permanently delete your account and data</p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences Tab -->
                        <div class="settings-tab" id="preferences-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-palette"></i> Appearance</h2>
                                </div>
                                <div class="form-group">
                                    <label>Theme</label>
                                    <select class="form-input">
                                        <option>Light Mode</option>
                                        <option>Dark Mode</option>
                                        <option>Auto (System)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-globe"></i> Language & Region</h2>
                                </div>
                                <div class="form-group">
                                    <label>Language</label>
                                    <select class="form-input">
                                        <option>English (US)</option>
                                        <option>Bangla</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Timezone</label>
                                    <select class="form-input">
                                        <option>Bangladesh Standard Time (BST)</option>
                                        <option>Indian Standard Time (IST)</option>
                                        <option>Coordinated Universal Time (UTC)</option>
                                        <option>Eastern Standard Time (EST)</option>
                                    </select>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn-save">Save Preferences</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);

            // Trigger reflow
            toast.offsetHeight;

            // Show toast
            toast.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('success') }}", 'success');
            });
        @endif



        // Handle cancel buttons
        document.querySelectorAll('.btn-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');
                if (form) {
                    form.reset();
                    showToast('Changes discarded', 'info');
                }
            });
        });

        // Settings tab switching
        document.querySelectorAll('.settings-nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const tabId = this.dataset.tab;

                // Update nav items
                document.querySelectorAll('.settings-nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');

                // Update tabs
                document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.remove('active'));
                document.getElementById(tabId + '-tab').classList.add('active');
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
