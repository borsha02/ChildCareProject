<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/users.css'])
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
                    <a href="{{ route('admin.users') }}" class="nav-item active">
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
                    <h1>User Management</h1>
                </div>
                <div class="top-bar-actions">
                    <button class="add-btn" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Add New User
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Users Table -->
                <div class="users-card">
                    <div class="card-header">
                        <h3>All Users</h3>
                        <div class="filter-tabs">
                            <button class="tab-btn active" onclick="filterByRole('all')">All</button>
                            <button class="tab-btn" onclick="filterByRole('admin')">Admins</button>
                            <button class="tab-btn" onclick="filterByRole('parent')">Parents</button>
                            <button class="tab-btn" onclick="filterByRole('caregiver')">Staff</button>
                        </div>
                    </div>

                    <div class="search-box">
                        <input type="text" placeholder="Search by name, email, or phone..." id="searchInput">
                        <i class="fas fa-search"></i>
                    </div>

                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <!-- Sample Users -->
                            <tr data-role="admin" data-status="active">
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar-small">JD</div>
                                        <div class="user-details-small">
                                            <h4>John Doe</h4>
                                            <p>ID: U001</p>
                                        </div>
                                    </div>
                                </td>
                                <td>john.doe@childcare.com</td>
                                <td>+1 234-567-8901</td>
                                <td><span class="role-badge admin">Admin</span></td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon edit" title="Edit" onclick="editUser(1)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon role" title="Assign Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <button class="action-icon delete" title="Delete" onclick="deleteUser(1)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role="parent" data-status="active">
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar-small" style="background: linear-gradient(135deg, #10b981, #059669);">SM</div>
                                        <div class="user-details-small">
                                            <h4>Sarah Martinez</h4>
                                            <p>ID: U002</p>
                                        </div>
                                    </div>
                                </td>
                                <td>sarah.m@email.com</td>
                                <td>+1 234-567-8902</td>
                                <td><span class="role-badge parent">Parent</span></td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon edit" title="Edit" onclick="editUser(2)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon role" title="Assign Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <button class="action-icon delete" title="Delete" onclick="deleteUser(2)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role="caregiver" data-status="active">
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar-small" style="background: linear-gradient(135deg, #f59e0b, #d97706);">LJ</div>
                                        <div class="user-details-small">
                                            <h4>Lisa Johnson</h4>
                                            <p>ID: U003</p>
                                        </div>
                                    </div>
                                </td>
                                <td>lisa.j@childcare.com</td>
                                <td>+1 234-567-8903</td>
                                <td><span class="role-badge caregiver">Caregiver</span></td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon edit" title="Edit" onclick="editUser(3)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon role" title="Assign Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <button class="action-icon delete" title="Delete" onclick="deleteUser(3)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role="parent" data-status="active">
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar-small" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">MJ</div>
                                        <div class="user-details-small">
                                            <h4>Michael Johnson</h4>
                                            <p>ID: U004</p>
                                        </div>
                                    </div>
                                </td>
                                <td>michael.j@email.com</td>
                                <td>+1 234-567-8904</td>
                                <td><span class="role-badge parent">Parent</span></td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon edit" title="Edit" onclick="editUser(4)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon role" title="Assign Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <button class="action-icon delete" title="Delete" onclick="deleteUser(4)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role="parent" data-status="inactive">
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar-small" style="background: linear-gradient(135deg, #ef4444, #dc2626);">DB</div>
                                        <div class="user-details-small">
                                            <h4>David Brown</h4>
                                            <p>ID: U005</p>
                                        </div>
                                    </div>
                                </td>
                                <td>david.b@email.com</td>
                                <td>+1 234-567-8905</td>
                                <td><span class="role-badge parent">Parent</span></td>
                                <td><span class="status-badge inactive">Inactive</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon edit" title="Edit" onclick="editUser(5)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon role" title="Assign Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <button class="action-icon delete" title="Delete" onclick="deleteUser(5)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role="caregiver" data-status="active">
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar-small" style="background: linear-gradient(135deg, #ec4899, #db2777);">ED</div>
                                        <div class="user-details-small">
                                            <h4>Emily Davis</h4>
                                            <p>ID: U006</p>
                                        </div>
                                    </div>
                                </td>
                                <td>emily.d@childcare.com</td>
                                <td>+1 234-567-8906</td>
                                <td><span class="role-badge caregiver">Caregiver</span></td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon edit" title="Edit" onclick="editUser(6)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon role" title="Assign Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <button class="action-icon delete" title="Delete" onclick="deleteUser(6)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal-overlay" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New User</h2>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Enter full name">
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Enter email address">
                </div>
                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Enter phone number">
                </div>
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="parent">Parent</option>
                        <option value="caregiver">Caregiver</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm password">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('userModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('userModal').classList.remove('active');
        }

        function editUser(id) {
            document.getElementById('userModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Edit User';
            console.log('Editing user:', id);
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                console.log('Deleting user:', id);
                alert('User deleted successfully!');
            }
        }

        function filterByRole(role) {
            const rows = document.querySelectorAll('#usersTableBody tr');
            const tabs = document.querySelectorAll('.tab-btn');

            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            rows.forEach(row => {
                row.style.display = (role === 'all' || row.dataset.role === role) ? '' : 'none';
            });
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

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
