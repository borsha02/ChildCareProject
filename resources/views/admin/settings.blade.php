<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/settings.css'])
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

                    <a href="{{ route('admin.communication') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Communication Logs</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.settings') }}" class="nav-item active">
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
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>System Settings</h1>
                </div>
            </div>

            <div class="content-area">
                <!-- Tabs Container -->
                <div class="tabs-container">
                    <div class="tabs-header">
                        <button class="tab active" onclick="switchTab('fees')">Fee Configuration</button>
                        <button class="tab" onclick="switchTab('hours')">Operating Hours</button>
                        <button class="tab" onclick="switchTab('classrooms')">Classrooms</button>
                        <button class="tab" onclick="switchTab('general')">General Settings</button>
                    </div>

                    <!-- Fee Configuration Tab -->
                    <div class="tab-content active" id="fees-tab">
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label for="registration_fee">Registration Fee</label>
                                <input type="number" id="registration_fee" value="150" placeholder="Enter amount">
                                <span class="setting-description">One-time fee for new enrollments</span>
                            </div>
                            <div class="setting-group">
                                <label for="monthly_fee">Monthly Tuition</label>
                                <input type="number" id="monthly_fee" value="1200" placeholder="Enter amount">
                                <span class="setting-description">Standard monthly fee per child</span>
                            </div>
                            <div class="setting-group">
                                <label for="late_pickup_fee">Late Pickup Fee</label>
                                <input type="number" id="late_pickup_fee" value="25" placeholder="Enter amount">
                                <span class="setting-description">Fee charged per 15 minutes late</span>
                            </div>
                            <div class="setting-group">
                                <label for="meal_fee">Daily Meal Fee</label>
                                <input type="number" id="meal_fee" value="15" placeholder="Enter amount">
                                <span class="setting-description">Optional meal service fee</span>
                            </div>
                            <div class="setting-group">
                                <label for="activity_fee">Activity Fee</label>
                                <input type="number" id="activity_fee" value="50" placeholder="Enter amount">
                                <span class="setting-description">Monthly fee for special activities</span>
                            </div>
                            <div class="setting-group">
                                <label for="sibling_discount">Sibling Discount (%)</label>
                                <input type="number" id="sibling_discount" value="10" placeholder="Enter percentage">
                                <span class="setting-description">Discount for second child onwards</span>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours Tab -->
                    <div class="tab-content" id="hours-tab">
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label for="opening_time">Opening Time</label>
                                <input type="time" id="opening_time" value="07:00">
                                <span class="setting-description">Facility opens at</span>
                            </div>
                            <div class="setting-group">
                                <label for="closing_time">Closing Time</label>
                                <input type="time" id="closing_time" value="18:00">
                                <span class="setting-description">Facility closes at</span>
                            </div>
                            <div class="setting-group">
                                <label for="breakfast_time">Breakfast Time</label>
                                <input type="time" id="breakfast_time" value="08:00">
                                <span class="setting-description">Breakfast served at</span>
                            </div>
                            <div class="setting-group">
                                <label for="lunch_time">Lunch Time</label>
                                <input type="time" id="lunch_time" value="12:00">
                                <span class="setting-description">Lunch served at</span>
                            </div>
                            <div class="setting-group">
                                <label for="snack_time">Snack Time</label>
                                <input type="time" id="snack_time" value="15:00">
                                <span class="setting-description">Afternoon snack at</span>
                            </div>
                            <div class="setting-group">
                                <label for="nap_time">Nap Time</label>
                                <input type="time" id="nap_time" value="13:00">
                                <span class="setting-description">Nap period starts at</span>
                            </div>
                        </div>
                    </div>

                    <!-- Classrooms Tab -->
                    <div class="tab-content" id="classrooms-tab">
                        <div class="classroom-list">
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>Toddler A</h4>
                                    <p>Ages 1-2 • Capacity: 10 children • Teacher: Lisa Johnson</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>Toddler B</h4>
                                    <p>Ages 1-2 • Capacity: 10 children • Teacher: Mike Thompson</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>Preschool A</h4>
                                    <p>Ages 3-4 • Capacity: 15 children • Teacher: Sarah Williams</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>Preschool B</h4>
                                    <p>Ages 3-4 • Capacity: 15 children • Teacher: Jennifer Davis</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>Kindergarten</h4>
                                    <p>Ages 5-6 • Capacity: 20 children • Teacher: Emily Davis</p>
                                </div>
                                <div class="classroom-actions">
                                    <button class="icon-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button class="add-btn" style="margin-top: 20px;">
                            <i class="fas fa-plus"></i>
                            Add New Classroom
                        </button>
                    </div>

                    <!-- General Settings Tab -->
                    <div class="tab-content" id="general-tab">
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label for="center_name">Center Name</label>
                                <input type="text" id="center_name" value="Little Stars Childcare" placeholder="Enter center name">
                                <span class="setting-description">Name of your childcare facility</span>
                            </div>
                            <div class="setting-group">
                                <label for="center_phone">Contact Phone</label>
                                <input type="tel" id="center_phone" value="+1 234-567-8900" placeholder="Enter phone number">
                                <span class="setting-description">Main contact number</span>
                            </div>
                            <div class="setting-group">
                                <label for="center_email">Contact Email</label>
                                <input type="email" id="center_email" value="info@littlestars.com" placeholder="Enter email address">
                                <span class="setting-description">Main contact email</span>
                            </div>
                            <div class="setting-group">
                                <label for="center_address">Address</label>
                                <input type="text" id="center_address" value="123 Main Street, City, State 12345" placeholder="Enter address">
                                <span class="setting-description">Physical address</span>
                            </div>
                            <div class="setting-group">
                                <label for="max_capacity">Maximum Capacity</label>
                                <input type="number" id="max_capacity" value="70" placeholder="Enter capacity">
                                <span class="setting-description">Total children capacity</span>
                            </div>
                            <div class="setting-group">
                                <label for="timezone">Timezone</label>
                                <select id="timezone">
                                    <option value="EST">Eastern Time (EST)</option>
                                    <option value="CST">Central Time (CST)</option>
                                    <option value="MST">Mountain Time (MST)</option>
                                    <option value="PST">Pacific Time (PST)</option>
                                </select>
                                <span class="setting-description">System timezone</span>
                            </div>
                        </div>
                    </div>

                    <!-- Save Section -->
                    <div class="save-section">
                        <button class="reset-btn">
                            <i class="fas fa-undo"></i>
                            Reset to Defaults
                        </button>
                        <button class="save-btn" onclick="saveSettings()">
                            <i class="fas fa-save"></i>
                            Save All Changes
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');

            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        function saveSettings() {
            if (confirm('Save all settings changes?')) {
                alert('Settings saved successfully!');
                console.log('Saving settings...');
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
