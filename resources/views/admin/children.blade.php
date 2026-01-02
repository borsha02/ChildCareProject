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
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Child Records Management</h1>
                </div>
                <div class="top-bar-actions">
                    <button class="add-btn" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Add New Child
                    </button>
                </div>
            </div>

            <div class="content-area">
                
                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab('requests')">
                        Registration Requests
                        @if($pendingChildren->count() > 0)
                            <span class="badge">{{ $pendingChildren->count() }}</span>
                        @endif
                    </button>
                    <button class="tab-btn" onclick="switchTab('enrolled')">Enrolled Children</button>
                </div>

                <!-- Registration Requests Section -->
                <div id="requestsTab" class="tab-content">
                    @if($pendingChildren->count() > 0)
                    <div class="children-grid">
                        @foreach($pendingChildren as $child)
                        <div class="child-card request-card" data-class="{{ $child->class }}">
                            <div class="child-header">
                                <div class="child-photo" style="background: {{ '#' . substr(md5($child->first_name . $child->last_name), 0, 6) }};">
                                    {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                                </div>
                                <div class="child-info">
                                    <h3>{{ $child->first_name }} {{ $child->last_name }}</h3>
                                    <div class="child-id">Applied: {{ $child->created_at->diffForHumans() }}</div>
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                            <div class="child-details">
                                <div class="detail-item">
                                    <div class="detail-label">Age</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($child->dob)->age }} years</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Class</div>
                                    <div class="detail-value">{{ ucfirst($child->class) }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Parent</div>
                                    <div class="detail-value">{{ $child->parent->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="child-actions">
                                <form action="{{ route('admin.children.approve', $child->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.children.reject', $child->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to reject this registration?');">
                                    @csrf
                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                                <button class="action-btn view" onclick="viewChild({{ json_encode($child) }})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No pending registration requests.</p>
                    </div>
                    @endif
                </div>

                <!-- Enrolled Children Section -->
                <div id="enrolledTab" class="tab-content" style="display: none;">
                    <!-- Search and Filter Controls -->
                    <div class="controls-bar">
                        <input type="text" class="search-input" id="searchInput" placeholder="Search by name, ID, or parent...">
                        <select class="filter-select" id="classFilter">
                            <option value="">All Classes</option>
                            <option value="Toddler">Toddler</option>
                            <option value="Preschool">Preschool</option>
                            <option value="Pre-K">Pre-K</option>
                            <option value="Young Learners">Young Learners</option>
                        </select>
                         <select class="filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select class="filter-select" id="packageFilter">
                            <option value="">All Packages</option>
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>

                    <div class="children-grid" id="childrenGrid">
                        @forelse($enrolledChildren as $child)
                        <div class="child-card" data-class="{{ $child->class }}" data-status="{{ $child->status }}" data-package="{{ $child->package ?? 'monthly' }}">
                            <div class="child-header">
                                <div class="child-photo" style="background: {{ '#' . substr(md5($child->first_name . $child->last_name), 0, 6) }};">
                                    {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                                </div>
                                <div class="child-info">
                                    <h3>{{ $child->first_name }} {{ $child->last_name }}</h3>
                                    <div class="child-id">ID: CH{{ str_pad($child->id, 3, '0', STR_PAD_LEFT) }}</div>
                                    <span class="status-badge {{ $child->status }}">{{ ucfirst($child->status) }}</span>
                                </div>
                            </div>
                            <div class="child-details">
                                <div class="detail-item">
                                    <div class="detail-label">Age</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($child->dob)->age }} years</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Class</div>
                                    <div class="detail-value">{{ ucfirst($child->class) }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Parent</div>
                                    <div class="detail-value">{{ $child->parent->name ?? 'N/A' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Enrollment</div>
                                    <div class="detail-value">{{ $child->created_at->format('M Y') }}</div>
                                </div>
                            </div>
                            <div class="child-actions">
                                <button class="action-btn view" onclick="viewChild({{ json_encode($child) }})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" onclick="editChild({{ json_encode($child) }}, '{{ route('admin.children.update', $child->id) }}')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.children.delete', $child->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this child record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <p>No enrolled children found.</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination if needed -->
                    @if($enrolledChildren->hasPages())
                        <div style="margin-top: 20px;">
                            {{ $enrolledChildren->links() }}
                        </div>
                    @endif
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
                        <label for="first_name">First Name <span style="color: red">*</span></label>
                        <input type="text" id="first_name" name="first_name" required placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span style="color: red">*</span></label>
                        <input type="text" id="last_name" name="last_name" required placeholder="Enter last name">
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth <span style="color: red">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender <span style="color: red">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="class">Class <span style="color: red">*</span></label>
                        <select id="class" name="class" required>
                            <option value="">Select class</option>
                            <option value="Toddler">Toddler</option>
                            <option value="Preschool">Preschool</option>
                            <option value="Pre-K">Pre-K</option>
                            <option value="Young Learners">Young Learners</option>
                            <option value="Young Learners">Young Learners</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="package">Package <span style="color: red">*</span></label>
                        <select id="package" name="package" required>
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date <span style="color: red">*</span></label>
                        <input type="date" id="enrollment_date" name="enrollment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_name">Parent/Guardian Name <span style="color: red">*</span></label>
                        <input type="text" id="parent_name" name="parent_name" required placeholder="Enter parent name">
                    </div>
                    <div class="form-group">
                        <label for="parent_phone">Parent Phone <span style="color: red">*</span></label>
                        <input type="tel" id="parent_phone" name="parent_phone" required placeholder="Enter phone number">
                    </div>
                    <div class="form-group full-width">
                        <label for="parent_email">Parent Email <span style="color: red">*</span></label>
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
    <!-- View Child Details Modal -->
    <div class="modal-overlay" id="viewChildModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Child Details</h2>
                <button class="close-modal" onclick="closeViewModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Details populated via JS -->
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Tab Switcher
        function switchTab(tabName) {
            // Update Tab Buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Update Tab Content
            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            
            if (tabName === 'requests') {
                document.getElementById('requestsTab').style.display = 'block';
            } else {
                document.getElementById('enrolledTab').style.display = 'block';
            }
        }

        // Modal functions
        function openModal() {
            document.getElementById('childModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Add New Child';
            const form = document.getElementById('childForm');
            form.action = "{{ route('admin.children.create') }}";
            form.reset();
            
            // Remove method spoofing if exists
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        }

        function closeModal() {
            document.getElementById('childModal').classList.remove('active');
        }

        function editChild(child, updateUrl) {
            document.getElementById('childModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Edit Child Record';
            
            const form = document.getElementById('childForm');
            form.action = updateUrl;

            // Add method spoofing for PUT
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }

            // Populate fields
            document.getElementById('first_name').value = child.first_name;
            document.getElementById('last_name').value = child.last_name;
            document.getElementById('date_of_birth').value = child.dob ? child.dob.split('T')[0] : '';
            document.getElementById('gender').value = child.gender;
            document.getElementById('class').value = child.class;
            document.getElementById('package').value = child.package || 'monthly';
            
            // Enrollment date from created_at
            if (child.created_at) {
                document.getElementById('enrollment_date').value = child.created_at.split('T')[0];
            }

            // Parent info
            if (child.parent) {
                document.getElementById('parent_name').value = child.parent.name;
                document.getElementById('parent_email').value = child.parent.email;
                document.getElementById('parent_phone').value = child.parent.phone;
            }

            document.getElementById('medical_info').value = child.medical_notes || '';
            // Address is not in DB currently
            document.getElementById('address').value = ''; 
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

        // Package filter
        document.getElementById('packageFilter').addEventListener('change', function(e) {
            const packageValue = e.target.value;
            const cards = document.querySelectorAll('.child-card');

            cards.forEach(card => {
                if (!packageValue || card.dataset.package === packageValue) {
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

        // View Modal Functions
        function viewChild(child) {
            const modal = document.getElementById('viewChildModal');
            const body = document.getElementById('viewModalBody');
            
            // Calculate age
            const dob = new Date(child.dob);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            body.innerHTML = `
                <div class="view-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Full Name</label>
                        <p>${child.first_name} ${child.last_name}</p>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Status</label>
                        <span class="status-badge ${child.status}" style="padding: 2px 8px; border-radius: 4px; background: #eee;">${child.status.charAt(0).toUpperCase() + child.status.slice(1)}</span>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Date of Birth</label>
                        <p>${new Date(child.dob).toLocaleDateString()}</p>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Age</label>
                        <p>${age} years</p>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Gender</label>
                        <p>${child.gender.charAt(0).toUpperCase() + child.gender.slice(1)}</p>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Class</label>
                        <p>${child.class}</p>
                    </div>
                     <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Package</label>
                        <p>${child.package ? child.package.charAt(0).toUpperCase() + child.package.slice(1) : 'Monthly'}</p>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Blood Group</label>
                        <p>${child.blood_group || 'N/A'}</p>
                    </div>
                     <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Parent Name</label>
                        <p>${child.parent ? child.parent.name : 'N/A'}</p>
                    </div>
                    <div class="detail-group">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Parent Phone</label>
                        <p>${child.parent ? child.parent.phone : 'N/A'}</p>
                    </div>
                    <div class="detail-group" style="grid-column: 1 / -1;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Medical Notes</label>
                        <p style="background: #f9f9f9; padding: 10px; border-radius: 4px;">${child.medical_notes || 'No medical notes available.'}</p>
                    </div>
                     <div class="detail-group" style="grid-column: 1 / -1;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Allergies</label>
                        <p style="background: #f9f9f9; padding: 10px; border-radius: 4px;">${child.allergies || 'No allergies listed.'}</p>
                    </div>
                </div>
            `;

            modal.classList.add('active');
        }

        function closeViewModal() {
            document.getElementById('viewChildModal').classList.remove('active');
        }

        // Close view modal on outside click
        document.getElementById('viewChildModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });
    </script>
</body>

</html>
