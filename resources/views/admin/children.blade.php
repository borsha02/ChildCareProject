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


                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca; position: relative;">
                        <ul style="margin-left: 20px; margin-bottom: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 10px; background: none; border: none; color: inherit; cursor: pointer; opacity: 0.7; font-size: 16px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success" style="background: #d1fae5; color: #065f46; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #a7f3d0; position: relative;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: inherit; cursor: pointer; opacity: 0.7; font-size: 16px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca; position: relative;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: inherit; cursor: pointer; opacity: 0.7; font-size: 16px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning" style="background: #ffedd5; color: #9a3412; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fed7aa; position: relative;">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                        <button onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: inherit; cursor: pointer; opacity: 0.7; font-size: 16px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const alerts = document.querySelectorAll('.alert');
                        if (alerts.length > 0) {
                            setTimeout(function() {
                                alerts.forEach(function(alert) {
                                    alert.style.transition = 'opacity 0.5s ease';
                                    alert.style.opacity = '0';
                                    setTimeout(function() {
                                        alert.remove();
                                    }, 500);
                                });
                            }, 5000); // Auto dismiss after 5 seconds
                        }
                    });
                </script>
                
                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-btn active" data-tab="requests" onclick="switchTab('requests')">
                        Registration Requests
                        @if($pendingChildren->count() > 0)
                            <span class="badge">{{ $pendingChildren->count() }}</span>
                        @endif
                    </button>
                    <button class="tab-btn" data-tab="enrolled" onclick="switchTab('enrolled')">Enrolled Children</button>
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
                                    <div class="detail-label">Package</div>
                                    <div class="detail-value">
                                        {{ ucfirst($child->package) }}
                                        @if($child->package === 'weekly' && $child->duration)
                                            ({{ $child->duration }} wks)
                                        @endif
                                    </div>
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
                                    <h3>
                                        {{ $child->first_name }} {{ $child->last_name }} 
                                        <span style="font-size: 0.8em; color: #666; font-weight: normal;">({{ ucfirst($child->package ?? 'Monthly') }})</span>
                                    </h3>
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
                                    <div class="detail-label">Assigned Caregiver</div>
                                    <div class="detail-value">
                                        @if($child->caregivers->count() > 0)
                                            {{ $child->caregivers->pluck('name')->join(', ') }}
                                        @else
                                            <span style="color: #9ca3af; font-style: italic;">Unassigned</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Parent</div>
                                    <div class="detail-value">{{ $child->parent->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="child-actions">
                                @if($child->caregivers->count() > 0)
                                    <button class="action-btn approve" onclick="assignCaregiver({{ json_encode($child) }})" title="Manage Assignments">
                                        <i class="fas fa-user-check"></i> Assigned
                                    </button>
                                @else
                                    <button class="action-btn view" onclick="assignCaregiver({{ json_encode($child) }})">
                                        <i class="fas fa-user-plus"></i> Assign
                                    </button>
                                @endif
                                <button class="action-btn view" onclick="viewChild({{ json_encode($child) }})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" onclick="editChild({{ json_encode($child) }}, '{{ route('admin.children.update', $child->id) }}')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @if($child->status === 'inactive')
                                <form action="{{ route('admin.children.reactivate', $child->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Reactivating will reset the enrollment date to today. Continue?');">
                                    @csrf
                                    <button type="submit" class="action-btn approve" title="Reactivate Child">
                                        <i class="fas fa-redo"></i> Reactivate
                                    </button>
                                </form>
                                @endif
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
                        <label for="dob">Date of Birth <span style="color: red">*</span></label>
                        <input type="date" id="dob" name="dob" required>
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
                        <select id="package" name="package" required onchange="toggleDuration()">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                    <div class="form-group" id="durationGroup" style="display: none;">
                        <label for="duration">Duration (Weeks) <span style="color: red">*</span></label>
                        <select id="duration" name="duration">
                            <option value="">Select duration</option>
                            <option value="1">1 Week</option>
                            <option value="2">2 Weeks</option>
                            <option value="3">3 Weeks</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date <span style="color: red">*</span></label>
                        <input type="date" id="enrollment_date" name="enrollment_date" required onchange="checkWorkingDay(this); calculateEndDate()">
                    </div>
                    <div class="form-group" id="endDateGroup" style="display: none;">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" readonly disabled style="background-color: #f3f4f6;">
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
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <select id="blood_group" name="blood_group">
                            <option value="">Select blood group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="caregiver_id">Assign Caregiver</label>
                        <select id="caregiver_id" name="caregiver_id">
                            <option value="">Select caregiver (optional)</option>
                            @foreach($caregivers as $caregiver)
                                <option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="allergies">Allergies</label>
                        <textarea id="allergies" name="allergies" placeholder="List any allergies..."></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="medical_info">Medical Information</label>
                        <textarea id="medical_info" name="medical_info" placeholder="Medications, special needs, etc."></textarea>
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
    
     <!-- Assign Caregiver Modal -->
     <div class="modal-overlay" id="assignCaregiverModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Assign Caregiver</h2>
                <button class="close-modal" onclick="closeAssignModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="assignCaregiverForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="margin-bottom: 20px;">Assign a caregiver to <strong id="assignChildName"></strong></p>
                    
                    <div class="form-group full-width">
                        <label for="caregiver">Select Caregiver</label>
                        <select id="caregiverSelect" name="caregiver_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                            <option value="">-- Choose Caregiver --</option>
                            @foreach($caregivers as $caregiver)
                                <option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="currentCaregiversList" style="margin-top: 20px;">
                         <!-- List of currently assigned caregivers -->
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAssignModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab Switcher
        function switchTab(tabName) {
            // Update Tab Buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                if(btn.dataset.tab === tabName) btn.classList.add('active');
            });
            // document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            // event.currentTarget.classList.add('active');

            // Update Tab Content
            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            
            if (tabName === 'requests') {
                document.getElementById('requestsTab').style.display = 'block';
                // history.pushState(null, null, '#requests'); // Optional: Update URL without reload
            } else {
                document.getElementById('enrolledTab').style.display = 'block';
                // history.pushState(null, null, '#enrolled'); // Optional: Update URL without reload
            }
        }

        // Check hash on load
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1); // Remove '#'
            if (hash === 'enrolled') {
                switchTab('enrolled');
            } else if (hash === 'requests') {
                switchTab('requests');
            }
        });

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
            
            // Reset duration visibility
            toggleDuration();

            // Default enrollment date to today
            document.getElementById('enrollment_date').value = new Date().toLocaleDateString('en-CA');
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
            
            if (child.dob) {
                 document.getElementById('dob').value = child.dob.split('T')[0].split(' ')[0];
            }
            
            document.getElementById('gender').value = child.gender;
            document.getElementById('class').value = child.class;
            document.getElementById('package').value = child.package || 'monthly';
            
            // Trigger toggle to show/hide duration
            toggleDuration();
            
            if (child.package === 'weekly' && child.duration) {
                document.getElementById('duration').value = child.duration;
            }
            
            // Enrollment date
            if (child.enrollment_date) {
                document.getElementById('enrollment_date').value = child.enrollment_date.split('T')[0].split(' ')[0];
            } else if (child.created_at) {
                document.getElementById('enrollment_date').value = child.created_at.split('T')[0].split(' ')[0];
            }

            // Parent info
            if (child.parent) {
                document.getElementById('parent_name').value = child.parent.name;
                document.getElementById('parent_email').value = child.parent.email;
                document.getElementById('parent_phone').value = child.parent.phone;
            }

            document.getElementById('medical_info').value = child.medical_notes || '';
            document.getElementById('blood_group').value = child.blood_group || '';
            document.getElementById('allergies').value = child.allergies || '';
            
            // Caregiver select
             if (document.getElementById('caregiver_id')) {
                document.getElementById('caregiver_id').value = (child.caregivers && child.caregivers.length > 0) ? child.caregivers[0].id : '';
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
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Enrollment Date</label>
                        <p>${child.enrollment_date ? new Date(child.enrollment_date).toLocaleDateString() : (child.created_at ? new Date(child.created_at).toLocaleDateString() : 'N/A')}</p>
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
                         <label style="font-weight: bold; display: block; margin-bottom: 5px;">Assigned Caregivers</label>
                         <p>${(child.caregivers && child.caregivers.length > 0) ? child.caregivers.map(c => c.name).join(', ') : 'None'}</p>
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
        
         // Assign Caregiver Modal Functions
        function assignCaregiver(child) {
            const modal = document.getElementById('assignCaregiverModal');
            document.getElementById('assignChildName').textContent = child.first_name + ' ' + child.last_name;
            const form = document.getElementById('assignCaregiverForm');
            form.action = `/admin/children/${child.id}/assign-caregiver`;

            // Reset select
            document.getElementById('caregiverSelect').value = '';

            // Show current assignments
            const listContainer = document.getElementById('currentCaregiversList');
            if (child.caregivers && child.caregivers.length > 0) {
                let html = '<label style="font-weight: bold; display: block; margin-bottom: 8px;">Currently Assigned:</label>';
                html += '<ul style="list-style: none; padding: 0;">';
                child.caregivers.forEach(cg => {
                    html += `
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: #f3f4f6; margin-bottom: 5px; border-radius: 4px;">
                            <span>${cg.name}</span>
                            <button type="button" onclick="removeCaregiver(${child.id}, ${cg.id})" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </li>
                    `;
                });
                html += '</ul>';
                listContainer.innerHTML = html;
            } else {
                listContainer.innerHTML = '<p style="color: #6b7280; font-style: italic;">No caregivers currently assigned.</p>';
            }

            modal.classList.add('active');
        }

        function closeAssignModal() {
            document.getElementById('assignCaregiverModal').classList.remove('active');
        }
        
        function removeCaregiver(childId, caregiverId) {
            if(confirm('Are you sure you want to remove this caregiver assignment?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/children/${childId}/remove-caregiver`;
                
                const csrfToken = document.querySelector('input[name="_token"]').value;
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = csrfToken;
                form.appendChild(tokenField);

                const idField = document.createElement('input');
                idField.type = 'hidden';
                idField.name = 'caregiver_id';
                idField.value = caregiverId;
                form.appendChild(idField);

                document.body.appendChild(form);
                form.submit();
            }
        }
        
        document.getElementById('assignCaregiverModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });

        function toggleDuration() {
            const packageSelect = document.getElementById('package');
            const durationGroup = document.getElementById('durationGroup');
            const durationSelect = document.getElementById('duration');
            const endDateGroup = document.getElementById('endDateGroup');

            if (packageSelect.value === 'weekly') {
                durationGroup.style.display = 'block';
                durationSelect.required = true;
                endDateGroup.style.display = 'block';
                calculateEndDate();
            } else {
                durationGroup.style.display = 'none';
                durationSelect.required = false;
                durationSelect.value = '';
                endDateGroup.style.display = 'none';
                document.getElementById('end_date').value = '';
            }
        }

        function calculateEndDate() {
            const duration = parseInt(document.getElementById('duration').value);
            const enrollmentDateVal = document.getElementById('enrollment_date').value;
            const endDateInput = document.getElementById('end_date');

            if (duration && enrollmentDateVal) {
                const startDate = new Date(enrollmentDateVal);
                // Calculate raw end date: Start + (Weeks * 7) - 1 day (to be inclusive)
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + (duration * 7) - 1);

                // Adjust for weekends (Friday = 5, Saturday = 6 in JS getDay())
                // In Bangladesh/Middle East checks: Fri/Sat are off.
                // JS getDay(): 0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat
                
                // If End Date lands on Friday (5), move back to Thursday (4)
                if (endDate.getDay() === 5) {
                    endDate.setDate(endDate.getDate() - 1);
                } 
                // If End Date lands on Saturday (6), move back to Thursday (4)
                else if (endDate.getDay() === 6) {
                    endDate.setDate(endDate.getDate() - 2);
                }

                // Format to YYYY-MM-DD
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                
                endDateInput.value = `${year}-${month}-${day}`;
            } else {
                endDateInput.value = '';
            }
        }

        document.getElementById('duration').addEventListener('change', calculateEndDate);

        function checkWorkingDay(input) {
            if (!input.value) return;
            // Parse explicitly as local date to avoid timezone issues
            const parts = input.value.split('-');
            const myDate = new Date(parts[0], parts[1] - 1, parts[2]); 
            const day = myDate.getDay();
            
            // 5 = Friday, 6 = Saturday
            if (day === 5 || day === 6) {
                 alert('Please select a working day (Sunday to Thursday). Fridays and Saturdays are off days.');
                 input.value = '';
                 // Also clear end date if it depends on this
                 document.getElementById('end_date').value = '';
            }
        }

        // Check for tab parameter in URL
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam === 'enrolled') {
                switchTab('enrolled');
            }
        });
    </script>
</body>

</html>
