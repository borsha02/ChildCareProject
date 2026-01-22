<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/attendance.css'])
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

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
                    <h1>Attendance Monitoring</h1>
                </div>
                <div class="top-bar-actions">
                    <input type="date" id="attendance_date" class="date-picker" value="{{ $date }}" onchange="window.location.href='{{ route('admin.attendance') }}?date=' + this.value">
                    <button class="export-btn" onclick="window.location.href='{{ route('admin.attendance.export') }}?date={{ $date }}'">
                        <i class="fas fa-download"></i>
                        Export Report
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-label">Present Today</div>
                        <div class="stat-value">{{ $stats['present_today'] }}</div>
                        <div class="stat-percentage">Out of {{ $children->count() }} children</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-label">Absent Today</div>
                        <div class="stat-value">{{ $stats['absent_today'] }}</div>
                        <div class="stat-percentage">{{ $children->count() > 0 ? round(($stats['absent_today'] / $children->count()) * 100) : 0 }}% absence rate</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-label">Late Arrivals</div>
                        <div class="stat-value">{{ $stats['late_today'] }}</div>
                        <div class="stat-percentage">{{ $children->count() > 0 ? round(($stats['late_today'] / $children->count()) * 100) : 0 }}% late rate</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-label">Attendance Rate</div>
                        <div class="stat-value">{{ $stats['attendance_rate'] }}%</div>
                        <div class="stat-percentage">Daily Average</div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Today's Attendance</h3>
                        <div class="filter-tabs">
                            <select class="filter-select" id="packageFilter" onchange="filterAttendance()">
                                <option value="all">All Packages</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                            <button class="tab-btn active" onclick="filterStatus('all')">All</button>
                            <button class="tab-btn" onclick="filterStatus('present')">Present</button>
                            <button class="tab-btn" onclick="filterStatus('absent')">Absent</button>
                            <button class="tab-btn" onclick="filterStatus('late')">Late</button>
                        </div>
                    </div>

                    <div class="search-box">
                        <input type="text" placeholder="Search by child name or class..." id="searchInput">
                        <i class="fas fa-search"></i>
                    </div>

                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Child Name</th>
                                <th>Class</th>
                                <th>Check-in Time</th>
                                <th>Check-out Time</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            @forelse($children as $child)
                                @php
                                    $record = $child->attendances->first();
                                    $status = $record ? $record->status : 'present'; // Default to present
                                @endphp
                            <tr data-status="{{ $status }}" data-package="{{ strtolower($child->package) }}">
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar" style="background: {{ '#' . substr(md5($child->first_name . $child->last_name), 0, 6) }};">
                                            {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                                        </div>
                                        <div class="student-details">
                                            <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                                            <p>ID: CH{{ str_pad($child->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <!-- ... rest of row ... -->
                                <td>{{ $child->class }}</td>
                                <td>
                                    <input type="time" class="time-input check-in" value="{{ $record && $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('H:i') : '' }}">
                                </td>
                                <td>
                                    <input type="time" class="time-input check-out" value="{{ $record && $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('H:i') : '' }}">
                                </td>
                                <td>
                                    <select class="status-select" onchange="updateRowStyle(this)">
                                        <option value="present" {{ $status == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ $status == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="excused" {{ $status == 'excused' ? 'selected' : '' }}>Excused</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="notes-input" value="{{ $record ? $record->notes : '' }}" placeholder="Add notes...">
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-save-row" 
                                                id="btn-{{ $child->id }}"
                                                onclick="saveAttendance(this, {{ $child->id }})" 
                                                title="Save Attendance">
                                            Save
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">No enrolled children found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                         @if(method_exists($children, 'links'))
                            {{ $children->links() }}
                         @endif
                    </div>
                </div>
            </div>
            <!-- Toast Container -->
            <div class="toast-container" id="toastContainer"></div>
        </main>
    </div>

    <script>
        // Filter attendance by status
        let currentStatus = 'all';

        // Update status filter
        function filterStatus(status) {
            currentStatus = status;
            
            // Update Tab Active State
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            filterAttendance();
        }

        // Combined Filter Logic
        function filterAttendance() {
            const packageFilter = document.getElementById('packageFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTableBody tr');

            rows.forEach(row => {
                const rowStatus = row.dataset.status;
                const rowPackage = row.dataset.package;

                const statusMatch = (currentStatus === 'all') || (rowStatus === currentStatus);
                const packageMatch = (packageFilter === 'all') || (rowPackage === packageFilter);

                if (statusMatch && packageMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function updateRowStyle(select) {
            const row = select.closest('tr');
            const status = select.value;
            row.dataset.status = status;
        }

        function saveAttendance(btn, childId) {
            const row = btn.closest('tr');
            const status = row.querySelector('.status-select').value;
            const checkIn = row.querySelector('.check-in').value;
            const checkOut = row.querySelector('.check-out').value;
            const notes = row.querySelector('.notes-input').value; // Get notes
            const date = document.getElementById('attendance_date').value;
            const childName = row.querySelector('.student-details h4').innerText;

            // --- Validation Logic (Matches Caregiver View) ---
            let errorMessages = [];
            let hasError = false;
            
            // 1. Check-in time required for Present/Late status
            if ((status === 'present' || status === 'late') && !checkIn) {
                hasError = true;
                errorMessages.push(`Check-in time is required when status is ${status.charAt(0).toUpperCase() + status.slice(1)}`);
            }

            // 2. Validate time range for check-in
            if (checkIn && (checkIn < '08:00' || checkIn > '18:00')) {
                hasError = true;
                errorMessages.push(`Check-in time must be between 8:00 AM and 6:00 PM`);
            }

            // 3. Validate time range for check-out
            if (checkOut && (checkOut < '08:00' || checkOut > '18:30')) {
                hasError = true;
                errorMessages.push(`Check-out time must be between 8:00 AM and 6:30 PM`);
            }

            // 4. Validate check-out requires check-in
            if (checkOut && !checkIn) {
                hasError = true;
                errorMessages.push(`Check-in time is required before setting check-out time`);
            }

            // 5. Validate check-out is after check-in
            if (checkOut && checkIn && checkOut <= checkIn) {
                hasError = true;
                errorMessages.push(`Check-out time must be after check-in time`);
            }

            if (hasError) {
                showToast(errorMessages.join('\n'), 'error');
                resetBtn();
                return;
            }

            // Show saving state
            const originalText = 'Save';
            const originalBg = '#3b82f6'; // Blue
            
            btn.innerHTML = 'Saving...';
            btn.disabled = true;
            btn.style.opacity = '0.7';

            fetch('{{ route("admin.attendance.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    child_id: childId,
                    date: date,
                    status: status,
                    check_in_time: checkIn,
                    check_out_time: checkOut,
                    notes: notes 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update row data attribute
                    row.dataset.status = status;
                    
                    // Success State
                    btn.innerHTML = 'Saved!';
                    btn.style.backgroundColor = '#10b981'; // Green
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    
                    showToast('Attendance saved successfully!', 'success');
                } else {
                    showToast(data.message || 'Error saving attendance', 'error');
                    resetBtn();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Try to parse validation error response if possible
                if (error.status === 422 && error.data) {
                    let msg = '';
                    if (error.data.errors) {
                        for (let key in error.data.errors) {
                            msg += error.data.errors[key][0] + '\n';
                        }
                    } else {
                        msg = error.data.message || 'Validation error';
                    }
                    showToast(msg, 'error');
                } else {
                    showToast('An error occurred while saving.', 'error');
                }
                resetBtn();
            });

            function resetBtn() {
                btn.innerHTML = 'Save';
                btn.style.backgroundColor = '#ef4444'; // Red error indication
                btn.disabled = false;
                btn.style.opacity = '1';

                setTimeout(() => {
                    // Only revert if the user hasn't clicked again (we could track this, but simple revert is usually fine)
                    // If the text is still 'Save' and bg is Red, revert it.
                    if (btn.innerHTML === 'Save' && btn.style.backgroundColor === 'rgb(239, 68, 68)') { // check for red
                         btn.style.backgroundColor = ''; // Revert to CSS default
                    }
                     // Or just force revert to be safe
                     btn.style.backgroundColor = ''; 
                }, 2000);
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type} show`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            toast.innerHTML = `
                <i class="fas ${icon} toast-icon"></i>
                <span class="toast-message">${message.replace(/\n/g, '<br>')}</span>
            `;

            container.appendChild(toast);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Reset button state when any input changes in a row
        document.querySelectorAll('.attendance-table tbody tr').forEach(row => {
            row.querySelectorAll('input, select').forEach(input => {
                const resetRowBtn = () => {
                    const btn = row.querySelector('.btn-save-row');
                    if (btn) {
                        btn.innerText = 'Save';
                        btn.style.backgroundColor = ''; // Revert to original CSS color
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    }
                };

                input.addEventListener('change', resetRowBtn);
                if (input.tagName === 'INPUT') {
                    input.addEventListener('input', resetRowBtn);
                }
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

        // Ensure correct date based on client timezone
        document.addEventListener('DOMContentLoaded', () => {
             const dateInput = document.getElementById('attendance_date');
             if (!dateInput) return;
 
             const urlParams = new URLSearchParams(window.location.search);
             const hasDateParam = urlParams.has('date');
 
             if (!hasDateParam) {
                 const serverDate = dateInput.value;
                 const clientDate = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
 
                 if (serverDate !== clientDate) {
                     // Redirect to client date to load correct data
                     window.location.search = `?date=${clientDate}`;
                 }
             }
        });
    </script>
</body>

</html>
