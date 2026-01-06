<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/job-applications.css'])
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
                    <a href="{{ route('admin.job-applications') }}" class="nav-item active">
                        <i class="fas fa-briefcase"></i>
                        <span>Job Applications</span>
                        @if(isset($pendingJobAppsCount) && $pendingJobAppsCount > 0)
                            <span class="badge" style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; margin-left: auto;">{{ $pendingJobAppsCount }}</span>
                        @endif
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
                <!-- ... other sections ... -->
                 <div class="nav-section">
                    <div class="nav-section-title">System</div>
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
                    <h1>Job Applications</h1>
                </div>
            </div>

            <div class="content-area">
                <!-- Job Applications Section -->
                <div class="job-applications-section">
                    <div class="section-header">
                        <h2>All Applications</h2>
                        <span class="pending-badge">{{ $jobApplications->where('status', 'pending')->count() }} Pending</span>
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
                                    @else
                                        <form action="{{ route('admin.job-applications.delete', $application->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="reject-app-btn" style="background: #fee2e2; color: #991b1b;">
                                                <i class="fas fa-trash"></i> Delete
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
            </div>
        </main>
    </div>

    <!-- Add Staff Modal (Used for Approval) -->
    <div class="modal" id="addStaffModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Approve & Add Staff Member</h2>
                <button class="close-modal" onclick="closeAddStaffModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm" action="{{ route('admin.staff.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="application_id" id="application_id">
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" required readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Set Password</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" required style="padding-right: 40px;">
                                <button type="button" onclick="togglePassword('password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <div style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation" required style="padding-right: 40px;">
                                <button type="button" onclick="togglePassword('password_confirmation', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
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
                        <button type="submit" class="btn-submit">Confirm Approval</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddStaffModal(application) {
            const modal = document.getElementById('addStaffModal');
            const form = document.getElementById('addStaffForm');
            
            form.reset();
            
            document.getElementById('application_id').value = application.id;
            document.getElementById('name').value = application.full_name;
            document.getElementById('email').value = application.email;
            document.getElementById('phone').value = application.phone_number;
            document.getElementById('position').value = application.position;

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

        // Mobile toggle
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
