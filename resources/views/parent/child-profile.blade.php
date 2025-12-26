<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Profile - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/childprofile.css'])
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
                    <div class="user-avatar">JD</div>
                    <div class="user-details">
                        <h4>John Doe</h4>
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
                    <a href="{{ route('parent.child-profile') }}" class="nav-item active">
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
                    <h1>Child Profiles</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <button class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            <div class="content-area">
                <div class="profile-container">
                    <!-- Page Header -->
                    <div class="page-header">
                        <button class="add-child-btn" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            Add New Child
                        </button>
                    </div>

        <!-- Children Grid -->
        <div class="children-grid" id="childrenGrid">
            <!-- Sample Child Card 1 -->
            <div class="child-card">
                <div class="child-header">
                    <div class="child-avatar">EM</div>
                    <div class="child-info">
                        <h3>Emma Doe</h3>
                        <p class="age">4 years old</p>
                    </div>
                </div>

                <div class="child-details">
                    <div class="detail-item">
                        <i class="fas fa-birthday-cake"></i>
                        <span><strong>DOB:</strong> March 15, 2020</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-venus-mars"></i>
                        <span><strong>Gender:</strong> Female</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-school"></i>
                        <span><strong>Class:</strong> Preschool A</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-heartbeat"></i>
                        <span><strong>Blood Group:</strong> O+</span>
                    </div>
                </div>

                <div class="card-actions">
                    <button class="action-btn btn-view" onclick="viewChild(1)">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="action-btn btn-edit" onclick="editChild(1)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteChild(1)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Sample Child Card 2 -->
            <div class="child-card">
                <div class="child-header">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                    <div class="child-info">
                        <h3>Lucas James</h3>
                        <p class="age">3 years old</p>
                    </div>
                </div>

                <div class="child-details">
                    <div class="detail-item">
                        <i class="fas fa-birthday-cake"></i>
                        <span><strong>DOB:</strong> July 22, 2021</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-venus-mars"></i>
                        <span><strong>Gender:</strong> Male</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-school"></i>
                        <span><strong>Class:</strong> Toddler B</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-heartbeat"></i>
                        <span><strong>Blood Group:</strong> A+</span>
                    </div>
                </div>

                <div class="card-actions">
                    <button class="action-btn btn-view" onclick="viewChild(2)">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="action-btn btn-edit" onclick="editChild(2)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteChild(2)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State (hidden by default, show when no children) -->
        <div class="empty-state" style="display: none;" id="emptyState">
            <i class="fas fa-child"></i>
            <h3>No Children Added Yet</h3>
            <p>Click the "Add New Child" button to register your first child</p>
            <button class="add-child-btn" onclick="openAddModal()">
                <i class="fas fa-plus"></i>
                Add Your First Child
            </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Child Modal -->
    <div class="modal" id="childModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Child</h2>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="childForm">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" id="firstName" name="firstName" required placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" id="lastName" name="lastName" required placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth *</label>
                            <input type="date" id="dob" name="dob" required>
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
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bloodGroup">Blood Group</label>
                            <select id="bloodGroup" name="bloodGroup">
                                <option value="">Select blood group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="class">Class</label>
                            <select id="class" name="class">
                                <option value="">Select class</option>
                                <option value="Infant">Infant</option>
                                <option value="Toddler A">Toddler A</option>
                                <option value="Toddler B">Toddler B</option>
                                <option value="Preschool A">Preschool A</option>
                                <option value="Preschool B">Preschool B</option>
                                <option value="Kindergarten">Kindergarten</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <textarea id="allergies" name="allergies" placeholder="List any allergies (e.g., peanuts, dairy, etc.)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="medicalNotes">Medical Notes</label>
                        <textarea id="medicalNotes" name="medicalNotes" placeholder="Any medical conditions or special needs"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="emergencyContact">Emergency Contact</label>
                        <input type="tel" id="emergencyContact" name="emergencyContact" placeholder="Emergency contact number">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Child
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Child Details Modal -->
    <div class="modal" id="viewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Child Details</h2>
                <button class="close-modal" onclick="closeViewModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body" id="viewModalBody">
                <!-- Details will be populated by JavaScript -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Modal Functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Child';
            document.getElementById('childForm').reset();
            document.getElementById('childModal').classList.add('active');
        }

        function editChild(id) {
            document.getElementById('modalTitle').textContent = 'Edit Child';
            // Here you would populate the form with child data
            document.getElementById('childModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('childModal').classList.remove('active');
        }

        function viewChild(id) {
            // Sample data - replace with actual data
            const childData = {
                name: 'Emma Doe',
                dob: 'March 15, 2020',
                age: '4 years',
                gender: 'Female',
                bloodGroup: 'O+',
                class: 'Preschool A',
                allergies: 'None',
                medicalNotes: 'None',
                emergencyContact: '+1234567890'
            };

            const viewBody = document.getElementById('viewModalBody');
            viewBody.innerHTML = `
                <div class="child-details">
                    <div class="detail-item">
                        <i class="fas fa-user"></i>
                        <span><strong>Full Name:</strong> ${childData.name}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-birthday-cake"></i>
                        <span><strong>Date of Birth:</strong> ${childData.dob}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span><strong>Age:</strong> ${childData.age}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-venus-mars"></i>
                        <span><strong>Gender:</strong> ${childData.gender}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-heartbeat"></i>
                        <span><strong>Blood Group:</strong> ${childData.bloodGroup}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-school"></i>
                        <span><strong>Class:</strong> ${childData.class}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-allergies"></i>
                        <span><strong>Allergies:</strong> ${childData.allergies}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-notes-medical"></i>
                        <span><strong>Medical Notes:</strong> ${childData.medicalNotes}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <span><strong>Emergency Contact:</strong> ${childData.emergencyContact}</span>
                    </div>
                </div>
            `;

            document.getElementById('viewModal').classList.add('active');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.remove('active');
        }

        function deleteChild(id) {
            if (confirm('Are you sure you want to delete this child profile?')) {
                // Handle delete logic here
                alert('Child profile deleted successfully!');
            }
        }

        // Form submission
        document.getElementById('childForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Handle form submission here
            alert('Child profile saved successfully!');
            closeModal();
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
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
