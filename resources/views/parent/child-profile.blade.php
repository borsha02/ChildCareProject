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
                    <h2>Little Stars Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <h4>{{ auth()->user()->name }}</h4>
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
                        @php
                            $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge" style="{{ $unreadCount > 0 ? 'display:inline-block' : 'display:none' }}">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
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
                   <!-- <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div> -->
                    <a href="{{ route('parent.notifications') }}" class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
                    </a>
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
            @forelse($children as $child)
            <div class="child-card">
                <div class="child-header">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                    </div>
                    <div class="child-info">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <h3>{{ $child->first_name }} {{ $child->last_name }}</h3>
                            @php
                                $statusColors = [
                                    'active' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'inactive' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'activation_requested' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                ];
                                $status = $child->status ?? 'pending';
                                $colors = $statusColors[$status] ?? $statusColors['pending'];
                            @endphp
                            <span class="status-badge {{ $status }}" style="font-size: 0.7em; padding: 2px 8px; border-radius: 12px; background: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </div>
                        <p class="age">{{ \Carbon\Carbon::parse($child->dob)->age }} years old</p>
                    </div>
                </div>

                <div class="child-details">
                    <div class="detail-item">
                        <i class="fas fa-birthday-cake"></i>
                        <span><strong>DOB:</strong> {{ \Carbon\Carbon::parse($child->dob)->format('F j, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-venus-mars"></i>
                        <span><strong>Gender:</strong> {{ ucfirst($child->gender) }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-school"></i>
                        <span><strong>Class:</strong> {{ $child->class ?? 'Not Assigned' }}</span>
                    </div>
                <div class="detail-item">
                        <i class="fas fa-heartbeat"></i>
                        <span><strong>Blood Group:</strong> {{ $child->blood_group ?? 'N/A' }}</span>
                    </div>
                   <div class="detail-item">
                        <i class="fas fa-box"></i>
                        <span><strong>Package:</strong> {{ ucfirst($child->package ?? 'Monthly') }}</span>
                    </div>
                    @if($child->package === 'weekly')
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span><strong>Duration:</strong> {{ $child->duration }} Weeks</span>
                    </div>
                    @endif

                    @if($child->caregivers->count() > 0)
                    <div class="caregiver-section" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                        <h4 style="font-size: 0.9em; color: #64748b; margin-bottom: 10px;">Assigned Caregiver{{ $child->caregivers->count() > 1 ? 's' : '' }}</h4>
                        @foreach($child->caregivers as $caregiver)
                        <a href="{{ route('parent.caregivers') }}" class="caregiver-card" style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 8px; border-radius: 8px; margin-bottom: 5px; text-decoration: none; color: inherit; transition: all 0.2s;">
                            <div class="caregiver-avatar" style="width: 30px; height: 30px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 0.8em; font-weight: bold;">
                                {{ strtoupper(substr($caregiver->name, 0, 1)) }}
                            </div>
                            <div class="caregiver-info">
                                <span style="display: block; font-size: 0.9em; font-weight: 600; color: #334155;">{{ $caregiver->name }}</span>
                                <span style="display: block; font-size: 0.8em; color: #64748b;">Caregiver</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="card-actions">
                    <button class="action-btn btn-view" onclick="viewChild({{ $child->id }})">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="action-btn btn-edit" onclick="editChild({{ $child->id }})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="action-btn btn-delete" onclick="deleteChild({{ $child->id }})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    @if($child->status === 'inactive')
                    <button class="action-btn btn-edit" onclick="openReactivationModal({{ $child->id }})" style="background: #3b82f6; color: white; border: none;">
                        <i class="fas fa-redo"></i> Reactivate
                    </button>
                    @elseif($child->status === 'activation_requested')
                    <button class="action-btn" disabled style="opacity: 0.6; cursor: not-allowed; background: #93c5fd; color: white; border: none;">
                        <i class="fas fa-clock"></i> Requested
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-child"></i>
                <h3>No Children Added Yet</h3>
                <p>Click the "Add New Child" button to register your first child</p>
            </div>
            @endforelse
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

            <form id="childForm" action="{{ route('parent.child-profile.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="text-red-600">*</span></label>
                            <input type="text" id="firstName" name="first_name" required placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="text-red-600">*</span></label>
                            <input type="text" id="lastName" name="last_name" required placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth <span class="text-red-600">*</span></label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender <span class="text-red-600">*</span></label>
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
                            <select id="bloodGroup" name="blood_group">
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
                            <label for="class">Class <span class="text-red-600">*</span></label>
                            <select id="class" name="class" required>
                                <option value="">Select class</option>
                                <option value="Toddler">Toddler(1-2 years)</option>
                                <option value="Preschool">Preschool(3-4 years)</option>
                                <option value="Pre-K">Pre-K(4-5 years)</option>
                                <option value="Young Learners">Young Learners(6-7 years)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="package">Package <span class="text-red-600">*</span></label>
                            <select id="package" name="package" required>
                                <option value="monthly">Monthly</option>
                                <option value="weekly">Weekly</option>
                            </select>
                        </div>
                        <div class="form-group" id="durationGroup" style="display: none;">
                            <label for="duration">Duration (Weeks) <span class="text-red-600">*</span></label>
                            <select id="duration" name="duration">
                                <option value="">Select duration</option>
                                <option value="1">1 Week</option>
                                <option value="2">2 Weeks</option>
                                <option value="3">3 Weeks</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="enrollmentDate">Enrollment Date <span class="text-red-600">*</span></label>
                        <input type="date" id="enrollmentDate" name="enrollment_date" required onchange="checkWorkingDay(this); calculateEndDate()">
                    </div>
                    
                    <div class="form-group" id="endDateGroup" style="display: none;">
                        <label for="endDate">End Date</label>
                        <input type="date" id="endDate" readonly disabled style="background-color: #f3f4f6; cursor: not-allowed;">
                    </div>

                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <textarea id="allergies" name="allergies" placeholder="List any allergies (e.g., peanuts, dairy, etc.)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="medicalNotes">Medical Notes</label>
                        <textarea id="medicalNotes" name="medical_notes" placeholder="Any medical conditions or special needs"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="emergencyContact">Emergency Contact <span class="text-red-600">*</span></label>
                        <input type="tel" id="emergencyContact" name="emergency_contact" required placeholder="Emergency contact number">
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

    <!-- Reactivation Modal -->
    <div class="modal" id="reactivationModal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h2>Request Reactivation</h2>
                <button class="close-modal" onclick="closeReactivationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="reactivationForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="margin-bottom: 20px; color: #666;">Select duration for renewal:</p>
                    <div class="form-group">
                        <label for="reactivationDuration">Duration (Weeks) <span style="color: red">*</span></label>
                        <select id="reactivationDuration" name="duration" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                            <option value="">Select duration</option>
                            <option value="1">1 Week</option>
                            <option value="2">2 Weeks</option>
                            <option value="3">3 Weeks</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeReactivationModal()">Cancel</button>
                    <button type="submit" class="btn-submit" style="background: #3b82f6;">
                        <i class="fas fa-paper-plane"></i> Send Request
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
        const childrenData = @json($children);

        // Modal Functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Child';
            
            const form = document.getElementById('childForm');
            form.reset();
            form.action = "{{ route('parent.child-profile.store') }}";
            
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            document.getElementById('childModal').classList.add('active');
            
            // Set default enrollment date to today
            document.getElementById('enrollmentDate').value = new Date().toISOString().split('T')[0];

            // Reset duration visibility
            document.getElementById('durationGroup').style.display = 'none';
        }

        function editChild(id) {
            const child = childrenData.find(c => c.id === id);
            if (!child) return;

            document.getElementById('modalTitle').textContent = 'Edit Child';
            
            const form = document.getElementById('childForm');
            form.action = `/parent/child-profile/${id}`;
            
            // Add hidden method field for PUT
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }

            // Populate form fields
            document.getElementById('firstName').value = child.first_name;
            document.getElementById('lastName').value = child.last_name;
            document.getElementById('dob').value = child.dob.substring(0, 10);
            document.getElementById('gender').value = child.gender;
            document.getElementById('bloodGroup').value = child.blood_group || '';
            document.getElementById('allergies').value = child.allergies || '';
            document.getElementById('medicalNotes').value = child.medical_notes || '';
            document.getElementById('emergencyContact').value = child.emergency_contact;
            
            document.getElementById('emergencyContact').value = child.emergency_contact;
            document.getElementById('class').value = child.class || ''; 
            document.getElementById('package').value = child.package || 'monthly';
            
            // Handle duration visibility and value
            toggleDuration();
            if (child.package === 'weekly') {
                document.getElementById('duration').value = child.duration || '';
            }

            // Enrollment Date
            document.getElementById('enrollmentDate').value = child.enrollment_date 
                ? child.enrollment_date.substring(0, 10) 
                : (child.created_at ? child.created_at.substring(0, 10) : new Date().toISOString().split('T')[0]);
            
            document.getElementById('childModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('childModal').classList.remove('active');
        }

        function viewChild(id) {
            const child = childrenData.find(c => c.id === id);
            if (!child) return;

            // Calculate age safely
            const dob = new Date(child.dob);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            const viewBody = document.getElementById('viewModalBody');
            viewBody.innerHTML = `
                <div class="child-details">
                    <div class="detail-item">
                        <i class="fas fa-user"></i>
                        <span><strong>Full Name:</strong> ${child.first_name} ${child.last_name}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-birthday-cake"></i>
                        <span><strong>Date of Birth:</strong> ${new Date(child.dob).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span><strong>Age:</strong> ${age} years old</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-venus-mars"></i>
                        <span><strong>Gender:</strong> ${child.gender.charAt(0).toUpperCase() + child.gender.slice(1)}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar-check"></i>
                        <span><strong>Enrollment Date:</strong> ${child.enrollment_date ? new Date(child.enrollment_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : (child.created_at ? new Date(child.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A')}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-school"></i>
                        <span><strong>Class:</strong> ${child.class || 'Not Assigned'}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-heartbeat"></i>
                        <span><strong>Blood Group:</strong> ${child.blood_group || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                         <i class="fas fa-box"></i>
                        <span><strong>Package:</strong> ${child.package ? child.package.charAt(0).toUpperCase() + child.package.slice(1) : 'Monthly'}</span>
                    </div>
                    ${child.package === 'weekly' ? `
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span><strong>Duration:</strong> ${child.duration} Weeks</span>
                    </div>` : ''}
                    <div class="detail-item">
                        <i class="fas fa-allergies"></i>
                        <span><strong>Allergies:</strong> ${child.allergies || 'None'}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-notes-medical"></i>
                        <span><strong>Medical Notes:</strong> ${child.medical_notes || 'None'}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <span><strong>Emergency Contact:</strong> ${child.emergency_contact}</span>
                    </div>
                </div>
            `;

            document.getElementById('viewModal').classList.add('active');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.remove('active');
        }

        function deleteChild(id) {
            if (confirm('Are you sure you want to delete this child profile? This action cannot be undone.')) {
                // Create a form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/parent/child-profile/${id}`;
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Append form to body and submit
                document.body.appendChild(form);
                form.submit();
            }
        }



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

        // Reactivation Modal Functions
        function openReactivationModal(childId) {
            const modal = document.getElementById('reactivationModal');
            constform = document.getElementById('reactivationForm');
            const form = document.getElementById('reactivationForm'); // fix typo if any
            
            form.action = `/parent/child-profile/${childId}/request-activation`;
            modal.classList.add('active');
        }

        function closeReactivationModal() {
            document.getElementById('reactivationModal').classList.remove('active');
        }
        
        // Toggle Duration based on Package
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
                document.getElementById('endDate').value = '';
            }
        }

        function calculateEndDate() {
            const duration = parseInt(document.getElementById('duration').value);
            const enrollmentDateVal = document.getElementById('enrollmentDate').value;
            const endDateInput = document.getElementById('endDate');

            if (duration && enrollmentDateVal) {
                const startDate = new Date(enrollmentDateVal);
                // Calculate raw end date: Start + (Weeks * 7) - 1 day (to be inclusive)
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + (duration * 7) - 1);

                // Adjust for weekends (Friday = 5, Saturday = 6)
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

        // Add event listener to duration
        document.getElementById('duration').addEventListener('change', calculateEndDate);

        document.getElementById('package').addEventListener('change', toggleDuration);

        function checkWorkingDay(input) {
            if (!input.value) return;
            const parts = input.value.split('-');
            const myDate = new Date(parts[0], parts[1] - 1, parts[2]); 
            const day = myDate.getDay();
            
            if (day === 5 || day === 6) {
                 alert('Please select a working day (Sunday to Thursday). Fridays and Saturdays are off days.');
                 input.value = '';
                 document.getElementById('endDate').value = '';
            }
        }
    </script>
</body>
</html>
