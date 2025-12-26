<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Records - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/children.css'])
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
                    <a href="{{ route('admin.children') }}" class="nav-item active">
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
                <h1>Child Records Management</h1>
                <div class="top-bar-actions">
                    <button class="add-btn" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Add New Child
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Search and Filter Controls -->
                <div class="controls-bar">
                    <input type="text" class="search-input" id="searchInput" placeholder="Search by name, ID, or parent...">
                    <select class="filter-select" id="classFilter">
                        <option value="">All Classes</option>
                        <option value="toddler-a">Toddler A</option>
                        <option value="toddler-b">Toddler B</option>
                        <option value="preschool-a">Preschool A</option>
                        <option value="preschool-b">Preschool B</option>
                        <option value="kindergarten">Kindergarten</option>
                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Children Grid -->
                <div class="children-grid" id="childrenGrid">
                    <!-- Sample Child Cards -->
                    <div class="child-card" data-class="preschool-a" data-status="active">
                        <div class="child-header">
                            <div class="child-photo">EM</div>
                            <div class="child-info">
                                <h3>Emma Martinez</h3>
                                <div class="child-id">ID: CH001</div>
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                        <div class="child-details">
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">4 years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Preschool A</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Parent</div>
                                <div class="detail-value">Sarah Martinez</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Enrollment</div>
                                <div class="detail-value">Jan 2024</div>
                            </div>
                        </div>
                        <div class="child-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick="editChild(1)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteChild(1)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="child-card" data-class="toddler-b" data-status="active">
                        <div class="child-header">
                            <div class="child-photo" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                            <div class="child-info">
                                <h3>Lucas Johnson</h3>
                                <div class="child-id">ID: CH002</div>
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                        <div class="child-details">
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">3 years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Toddler B</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Parent</div>
                                <div class="detail-value">Michael Johnson</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Enrollment</div>
                                <div class="detail-value">Mar 2024</div>
                            </div>
                        </div>
                        <div class="child-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick="editChild(2)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteChild(2)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="child-card" data-class="kindergarten" data-status="active">
                        <div class="child-header">
                            <div class="child-photo" style="background: linear-gradient(135deg, #10b981, #059669);">SW</div>
                            <div class="child-info">
                                <h3>Sophia Williams</h3>
                                <div class="child-id">ID: CH003</div>
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                        <div class="child-details">
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">5 years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Kindergarten</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Parent</div>
                                <div class="detail-value">Jennifer Williams</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Enrollment</div>
                                <div class="detail-value">Sep 2023</div>
                            </div>
                        </div>
                        <div class="child-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick="editChild(3)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteChild(3)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="child-card" data-class="preschool-b" data-status="inactive">
                        <div class="child-header">
                            <div class="child-photo" style="background: linear-gradient(135deg, #f59e0b, #d97706);">OB</div>
                            <div class="child-info">
                                <h3>Oliver Brown</h3>
                                <div class="child-id">ID: CH004</div>
                                <span class="status-badge inactive">Inactive</span>
                            </div>
                        </div>
                        <div class="child-details">
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">4 years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Preschool B</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Parent</div>
                                <div class="detail-value">David Brown</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Enrollment</div>
                                <div class="detail-value">Jun 2023</div>
                            </div>
                        </div>
                        <div class="child-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick="editChild(4)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteChild(4)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="child-card" data-class="toddler-a" data-status="active">
                        <div class="child-header">
                            <div class="child-photo" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">AD</div>
                            <div class="child-info">
                                <h3>Ava Davis</h3>
                                <div class="child-id">ID: CH005</div>
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                        <div class="child-details">
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">2 years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Toddler A</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Parent</div>
                                <div class="detail-value">Emily Davis</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Enrollment</div>
                                <div class="detail-value">Feb 2024</div>
                            </div>
                        </div>
                        <div class="child-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick="editChild(5)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteChild(5)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="child-card" data-class="preschool-a" data-status="active">
                        <div class="child-header">
                            <div class="child-photo" style="background: linear-gradient(135deg, #ef4444, #dc2626);">NM</div>
                            <div class="child-info">
                                <h3>Noah Miller</h3>
                                <div class="child-id">ID: CH006</div>
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                        <div class="child-details">
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">4 years</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Class</div>
                                <div class="detail-value">Preschool A</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Parent</div>
                                <div class="detail-value">Robert Miller</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Enrollment</div>
                                <div class="detail-value">Apr 2024</div>
                            </div>
                        </div>
                        <div class="child-actions">
                            <button class="action-btn view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick="editChild(6)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteChild(6)">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Child Modal -->
    <div class="modal-overlay" id="childModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Child</h2>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.children.create') }}" method="POST" id="childForm">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required placeholder="Enter last name">
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="class">Class *</label>
                        <select id="class" name="class" required>
                            <option value="">Select class</option>
                            <option value="toddler-a">Toddler A</option>
                            <option value="toddler-b">Toddler B</option>
                            <option value="preschool-a">Preschool A</option>
                            <option value="preschool-b">Preschool B</option>
                            <option value="kindergarten">Kindergarten</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date *</label>
                        <input type="date" id="enrollment_date" name="enrollment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_name">Parent/Guardian Name *</label>
                        <input type="text" id="parent_name" name="parent_name" required placeholder="Enter parent name">
                    </div>
                    <div class="form-group">
                        <label for="parent_phone">Parent Phone *</label>
                        <input type="tel" id="parent_phone" name="parent_phone" required placeholder="Enter phone number">
                    </div>
                    <div class="form-group full-width">
                        <label for="parent_email">Parent Email *</label>
                        <input type="email" id="parent_email" name="parent_email" required placeholder="Enter email address">
                    </div>
                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" placeholder="Enter home address"></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="medical_info">Medical Information</label>
                        <textarea id="medical_info" name="medical_info" placeholder="Allergies, medications, special needs, etc."></textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Child
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal() {
            document.getElementById('childModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Add New Child';
            document.getElementById('childForm').reset();
        }

        function closeModal() {
            document.getElementById('childModal').classList.remove('active');
        }

        function editChild(id) {
            document.getElementById('childModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Edit Child Record';
            // In real app, load child data here
            console.log('Editing child:', id);
        }

        function deleteChild(id) {
            if (confirm('Are you sure you want to delete this child record? This action cannot be undone.')) {
                console.log('Deleting child:', id);
                alert('Child record deleted successfully!');
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.child-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Class filter
        document.getElementById('classFilter').addEventListener('change', function(e) {
            const classValue = e.target.value;
            const cards = document.querySelectorAll('.child-card');

            cards.forEach(card => {
                if (!classValue || card.dataset.class === classValue) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const statusValue = e.target.value;
            const cards = document.querySelectorAll('.child-card');

            cards.forEach(card => {
                if (!statusValue || card.dataset.status === statusValue) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Close modal when clicking outside
        document.getElementById('childModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
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
