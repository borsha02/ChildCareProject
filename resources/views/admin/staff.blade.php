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
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Staff Management</h1>
                </div>
                <div class="top-bar-actions">
                    <button class="add-btn" onclick="openAddStaffModal()">
                        <i class="fas fa-plus"></i>
                        Add Staff Member
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Job Applications Section -->
                <div class="job-applications-section">
                    <div class="section-header">
                        <h2>Job Applications</h2>
                        <span class="pending-badge" style="background: #e0f2fe; color: #0369a1;">{{ $jobApplications->where('status', 'pending')->count() }} Pending</span>
                    </div>
                    <div class="job-applications-grid">
                        @forelse($jobApplications as $application)
                            <div class="job-application-card">
                                <div class="application-header">
                                    <div class="applicant-info">
                                        <h3>{{ $application->full_name }}</h3>
                                        <p>{{ $application->position }}</p>
                                    </div>
                                    <span class="status-badge {{ $application->status }}">{{ ucfirst($application->status) }}</span>
                                </div>
                                <div class="application-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Email</div>
                                        <div class="detail-value">{{ $application->email }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Phone</div>
                                        <div class="detail-value">{{ $application->phone_number }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Applied On</div>
                                        <div class="detail-value">{{ $application->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="application-actions">
                                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="view-resume-btn">
                                        <i class="fas fa-file-alt"></i> View Resume
                                    </a>
                                    @if($application->status === 'pending')
                                        <button class="approve-app-btn" onclick='openAddStaffModal(@json($application))'>
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <form action="{{ route('admin.jobs.reject', $application->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to decline this application?');">
                                            @csrf
                                            <button type="submit" class="reject-app-btn">
                                                <i class="fas fa-times"></i> Decline
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #6b7280; padding: 20px;">No job applications found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Leave Requests Section -->
                <div class="leave-requests-section">
                    <div class="section-header">
                        <h2>Pending Leave Requests</h2>
                        <span class="pending-badge">{{ $leaveRequests->count() }} Pending</span>
                    </div>
                    <div class="leave-requests-grid">
                        @forelse($leaveRequests as $request)
                        <div class="leave-request-card">
                            <div class="leave-header">
                                <div class="leave-staff-info">
                                    <h3>{{ $request->user->name ?? 'Unknown User' }}</h3>
                                    <p>{{ ucfirst($request->user->role ?? 'Staff') }} • {{ $request->user->specialization ?? 'General Caregiver' }}</p>
                                </div>
                                <span class="leave-type-badge {{ strtolower(str_replace(' ', '-', $request->leave_type)) }}">{{ $request->leave_type }}</span>
                            </div>
                            <div class="leave-details">
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Start Date</div>
                                    <div class="leave-detail-value">{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">End Date</div>
                                    <div class="leave-detail-value">{{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Duration</div>
                                    <div class="leave-detail-value">{{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} Days</div>
                                </div>
                                <div class="leave-detail-item">
                                    <div class="leave-detail-label">Requested On</div>
                                    <div class="leave-detail-value">{{ $request->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="leave-reason">
                                <strong>Reason:</strong> {{ $request->reason }}
                            </div>
                            <div class="leave-actions">
                                <form action="{{ route('admin.leave.approve', $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="approve-btn" onclick="return confirm('Approve this leave request?');">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.leave.deny', $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="deny-btn" onclick="return confirm('Deny this leave request?');">
                                        <i class="fas fa-times"></i> Deny
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; color: #6b7280; padding: 20px; grid-column: 1/-1;">
                            <p>No pending leave requests found.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Staff Grid -->
                <div class="staff-grid">
                    @forelse($staff as $member)
                    <div class="staff-card">
                        <div class="staff-header">
                            <div class="staff-photo" style="background: linear-gradient(135deg, {{ '#' . substr(md5($member->id), 0, 6) }}, {{ '#' . substr(md5($member->email), 0, 6) }});">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                            <div class="staff-info">
                                <h3>{{ $member->name }}</h3>
                                <div class="staff-role">{{ $member->specialization ?? 'Caregiver' }}</div>
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
                                <div class="detail-value">--</div> 
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Shift</div>
                                <div class="detail-value">{{ ucfirst($member->shift) ?? '--' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Joined</div>
                                <div class="detail-value">{{ $member->created_at->format('M Y') }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value">{{ $member->phone }}</div>
                            </div>
                        </div>
                        <div class="staff-actions">
                            <button class="action-btn view" onclick='openViewStaffModal(@json($member))'>
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="action-btn edit" onclick='openEditStaffModal(@json($member))'>
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="if(confirm('Are you sure you want to delete this staff member?')) document.getElementById('delete-form-{{ $member->id }}').submit();">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                            <form id="delete-form-{{ $member->id }}" action="{{ route('admin.staff.delete', $member->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <!-- Actually, looking at routes:
                                46:    Route::get('/staff', ...
                                47:    Route::post('/staff/create', ...
                                48:    Route::put('/staff/{id}', ...
                                
                                There is NO delete route for staff! 
                                I should probably use toggle status or add a delete route.
                                Given the user request "staff profile card gulo dynamic koro", I will stick to what's there. 
                                But the static HTML had a "Delete" button.
                                I'll implement it as "Toggle Status" (Deactivate) or just hide it if not available?
                                I'll use a form to correct route if I can find one or just placeholder alert for now to not break app.
                                Actually, I'll add a proper delete route if I can, but I shouldn't go out of scope too much.
                                I'll leave the Delete button visual but maybe make it alert "Not implemented" or implement a soft delete.
                                Wait, I can see `toggleUserStatus` in AdminController (lines 173). That might be applicable if I use the user ID.
                                Route::post('/users/{id}/toggle-status'
                                I'll use that for now as "Delete/Deactivate".
                            -->

                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #6b7280;">
                        No staff members found.
                    </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- View Staff Modal -->
    <div class="modal" id="viewStaffModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Staff Details</h2>
                <button class="close-modal" onclick="closeViewStaffModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="staff-view-profile">
                    <div class="view-avatar"></div>
                    <h3 class="view-name"></h3>
                    <p class="view-role"></p>
                </div>
                
                <div class="view-details-grid">
                    <div class="view-item">
                        <label>Email Address</label>
                        <p id="view-email"></p>
                    </div>
                    <div class="view-item">
                        <label>Phone Number</label>
                        <p id="view-phone"></p>
                    </div>
                    <div class="view-item">
                        <label>Shift</label>
                        <p id="view-shift"></p>
                    </div>
                    <div class="view-item">
                        <label>Status</label>
                        <p id="view-status"></p>
                    </div>
                    <div class="view-item">
                        <label>Joined Date</label>
                        <p id="view-joined"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeViewStaffModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal" id="addStaffModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Staff Member</h2>
                <button class="close-modal" onclick="closeAddStaffModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm" action="{{ route('admin.staff.create') }}" method="POST">
                    @csrf
                    <div id="method-field"></div>
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="application_id" id="application_id">
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="position">Position/Role</label>
                            <select id="position" name="specialization" required>
                                <option value="" disabled selected>Select Position</option>
                                <option value="Senior teacher">Senior teacher</option>
                                <option value="Assistant teacher">Assistant teacher</option>
                                <option value="Junior teacher">Junior teacher</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shift">Shift</label>
                            <select id="shift" name="shift">
                                <option value="morning">Morning (7AM - 3PM)</option>
                                <option value="afternoon">Afternoon (3PM - 11PM)</option>
                                <option value="full-time">Full Time</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeAddStaffModal()">Cancel</button>
                        <button type="submit" class="btn-submit">Add Staff Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Staff Modal -->
    <div class="modal" id="viewStaffModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Staff Details</h2>
                <button class="close-modal" onclick="closeViewStaffModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="staff-view-profile">
                    <div class="view-avatar"></div>
                    <h3 class="view-name"></h3>
                    <p class="view-role"></p>
                </div>
                
                <div class="view-details-grid">
                    <div class="view-item">
                        <label>Email Address</label>
                        <p id="view-email"></p>
                    </div>
                    <div class="view-item">
                        <label>Phone Number</label>
                        <p id="view-phone"></p>
                    </div>
                    <div class="view-item">
                        <label>Shift</label>
                        <p id="view-shift"></p>
                    </div>
                    <div class="view-item">
                        <label>Status</label>
                        <p id="view-status"></p>
                    </div>
                    <div class="view-item">
                        <label>Joined Date</label>
                        <p id="view-joined"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeViewStaffModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openAddStaffModal(application = null) {
            const modal = document.getElementById('addStaffModal');
            const form = document.getElementById('addStaffForm');
            
            // Reset form
            form.reset();
            document.getElementById('application_id').value = '';

            // Prefill if application provided
            if (application) {
                document.getElementById('application_id').value = application.id;
                document.getElementById('name').value = application.full_name;
                document.getElementById('email').value = application.email;
                document.getElementById('phone').value = application.phone_number;
                document.getElementById('position').value = application.position;
            }



            // Reset UI for "Add" mode
            const modalTitle = modal.querySelector('.modal-header h2');
            const submitBtn = modal.querySelector('.btn-submit');
            
            if (modalTitle) modalTitle.textContent = 'Add Staff Member';
            if (submitBtn) submitBtn.textContent = 'Add Staff Member';
            
            form.action = "{{ route('admin.staff.create') }}";
            document.getElementById('method-field').innerHTML = '';
            
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;

            modal.classList.add('active');
        }

        function closeAddStaffModal() {
            document.getElementById('addStaffModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('addStaffModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('addStaffModal')) {
                closeAddStaffModal();
            }
        });

        function openEditStaffModal(staff) {
            const modal = document.getElementById('addStaffModal');
            const form = document.getElementById('addStaffForm');
            const modalTitle = modal.querySelector('.modal-header h2');
            const submitBtn = modal.querySelector('.btn-submit');
            
            // Update Modal UI
            modalTitle.textContent = 'Edit Staff Member';
            submitBtn.textContent = 'Update Staff Member';
            
            // Update Form Action and Method
            form.action = `/admin/staff/${staff.id}`;
            document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Prefill Data
            document.getElementById('user_id').value = staff.id;
            document.getElementById('name').value = staff.name;
            document.getElementById('email').value = staff.email;
            document.getElementById('phone').value = staff.phone;
            document.getElementById('position').value = staff.specialization || '';
            document.getElementById('shift').value = staff.shift || '';
            
            // Handle Password (Optional for edit)
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;

            modal.classList.add('active');
        }

        function openViewStaffModal(staff) {
            const modal = document.getElementById('viewStaffModal');
            
            // Set Avatar
            const avatar = modal.querySelector('.view-avatar');
            avatar.textContent = staff.name.substring(0, 2).toUpperCase();
            avatar.style.background = `linear-gradient(135deg, #${Math.floor(Math.random()*16777215).toString(16)}, #${Math.floor(Math.random()*16777215).toString(16)})`;

            // Set Text Content
            modal.querySelector('.view-name').textContent = staff.name;
            modal.querySelector('.view-role').textContent = staff.specialization || 'Caregiver';
            document.getElementById('view-email').textContent = staff.email;
            document.getElementById('view-phone').textContent = staff.phone;
            document.getElementById('view-shift').textContent = staff.shift ? staff.shift.charAt(0).toUpperCase() + staff.shift.slice(1) : '--';
            document.getElementById('view-status').textContent = staff.status.charAt(0).toUpperCase() + staff.status.slice(1);
            
            const date = new Date(staff.created_at);
            document.getElementById('view-joined').textContent = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

            modal.classList.add('active');
        }

        function closeViewStaffModal() {
            document.getElementById('viewStaffModal').classList.remove('active');
        }

        // Close View modal when clicking outside
        document.getElementById('viewStaffModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('viewStaffModal')) {
                closeViewStaffModal();
            }
        });

        function approveLeave(id) {
            if (confirm('Approve this leave request?')) {
                alert('Leave request approved successfully!');
                console.log('Approved leave:', id);
                // In real app, send to backend
            }
        }
        
        // ... rest of scripts


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

        // Auto-scroll to pending leave requests section if there are pending items
        window.addEventListener('load', () => {
            const pendingCount = {{ $leaveRequests->count() }};
            if (pendingCount > 0) {
                const leaveSection = document.querySelector('.leave-requests-section');
                if (leaveSection) {
                    setTimeout(() => {
                        leaveSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 300);
                }
            }
        });
    </script>
</body>
</html>
