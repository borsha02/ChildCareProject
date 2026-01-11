@extends('layouts.caregiver')

@section('title', 'Attendance')

@section('styles')
    @vite(['resources/css/caregiver/attendance.css'])
@endsection

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <div style="display: flex; align-items: center;">
            <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Attendance Tracking</h1>
        </div>
        <div class="top-bar-actions">
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="notification-dot"></span>
            </a>
            <a href="{{ route('caregiver.messages') }}"
                class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('caregiver.attendance') }}" method="GET" id="dateFilterForm">
            <div class="top-bar-actions filter-form">
                 <input type="date" name="date" value="{{ $date }}"
                    onchange="document.getElementById('dateFilterForm').submit()"
                    class="date-input">
            </div>
        </form>

        <!-- Stats Grid (Placeholder calculations or static for now as requested task is connection) -->
         <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->filter(fn($c) => $c->attendances->first()?->status === 'present')->count() }}</h3>
                    <p>Present</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->filter(fn($c) => $c->attendances->first()?->status === 'absent')->count() }}</h3>
                    <p>Absent</p>
                </div>
            </div>
             <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->filter(fn($c) => $c->attendances->first()?->status === 'late')->count() }}</h3>
                    <p>Late</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-comment-medical"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->filter(fn($c) => $c->attendances->first()?->status === 'excused')->count() }}</h3>
                    <p>Excused</p>
                </div>
            </div>
        </div>

        <!-- Attendance Marking -->
        <div class="card">
            <div class="card-header">
                <h3>Mark Attendance - {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h3>
            </div>
            <form action="{{ route('caregiver.attendance.store') }}" method="POST" onsubmit="return validateAllAttendance(event)">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="card-header card-header-actions">
                     <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save All
                    </button>
                </div>

                <div class="table-responsive">
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
                        <tbody>
                            @forelse($assignedChildren as $child)
                                @php
                                    $attendance = $child->attendances->first();
                                    $status = $attendance ? $attendance->status : 'present';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="child-info">
                                            <div class="child-avatar">
                                                {{ substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1) }}
                                            </div>
                                            <span class="child-name">{{ $child->first_name }} {{ $child->last_name }}</span>
                                        </div>
                                    </td>
                                    <td class="child-class">{{ $child->class ?? 'N/A' }}</td>
                                    <td>
                                        <input type="time" name="attendance[{{ $child->id }}][check_in_time]" value="{{ $attendance ? (\Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') ) : '' }}"
                                            class="time-input" min="08:00" max="18:00">
                                    </td>
                                    <td>
                                        <input type="time" name="attendance[{{ $child->id }}][check_out_time]" value="{{ $attendance && $attendance->check_out_time ? (\Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') ) : '' }}"
                                            class="time-input" min="08:00" max="18:00">
                                    </td>
                                    <td>
                                        <select name="attendance[{{ $child->id }}][status]" class="status-select">
                                            <option value="present" {{ $status == 'present' ? 'selected' : '' }}>Present</option>
                                            <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>Absent</option>
                                            <option value="late" {{ $status == 'late' ? 'selected' : '' }}>Late</option>
                                            <option value="excused" {{ $status == 'excused' ? 'selected' : '' }}>Excused</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="attendance[{{ $child->id }}][notes]" value="{{ $attendance ? $attendance->notes : '' }}" placeholder="Add notes..."
                                            class="notes-input">
                                    </td>
                                    <td>
                                        @php
                                            $isSaved = $attendance !== null;
                                        @endphp
                                        <button type="button" 
                                            onclick="saveChild({{ $child->id }})" 
                                            id="btn-{{ $child->id }}" 
                                            class="btn-save-row"
                                            style="{{ $isSaved ? 'background: #10b981;' : '' }}">
                                            {{ $isSaved ? 'Saved!' : 'Save' }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        No children assigned to you.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        function validateAllAttendance(event) {
            const timeInputs = document.querySelectorAll('input[type="time"]');
            let hasError = false;
            let errorMessages = [];

            // Get all attendance rows
            const rows = document.querySelectorAll('.attendance-table tbody tr');
            
            rows.forEach(row => {
                const checkInInput = row.querySelector('input[name*="check_in_time"]');
                const checkOutInput = row.querySelector('input[name*="check_out_time"]');
                
                if (!checkInInput || !checkOutInput) return;
                
                const checkIn = checkInInput.value;
                const checkOut = checkOutInput.value;
                const status = row.querySelector('select[name*="status"]')?.value;
                const childName = row.querySelector('.child-name')?.textContent || 'Unknown';

                // Validate check-in time is required for 'present' and 'late' statuses
                if ((status === 'present' || status === 'late') && !checkIn) {
                    hasError = true;
                    errorMessages.push(`${childName}: Check-in time is required when status is ${status.charAt(0).toUpperCase() + status.slice(1)}`);
                }

                // Validate time range for check-in
                if (checkIn && (checkIn < '08:00' || checkIn > '18:00')) {
                    hasError = true;
                    errorMessages.push(`${childName}: Check-in time must be between 8:00 AM and 6:30 PM`);
                }

                // Validate time range for check-out
                if (checkOut && (checkOut < '08:00' || checkOut > '18:30')) {
                    hasError = true;
                    errorMessages.push(`${childName}: Check-out time must be between 8:00 AM and 6:30 PM`);
                }

                // Validate check-out requires check-in
                if (checkOut && !checkIn) {
                    hasError = true;
                    errorMessages.push(`${childName}: Check-in time is required before setting check-out time`);
                }

                // Validate check-out is after check-in
                if (checkOut && checkIn && checkOut <= checkIn) {
                    hasError = true;
                    errorMessages.push(`${childName}: Check-out time must be after check-in time`);
                }
            });

            if (hasError) {
                event.preventDefault();
                showToast(errorMessages.join('\n'), 'error');
                return false;
            }

            return true;
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
                    container.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Reset button state when any input changes in a row
        document.querySelectorAll('.attendance-table tbody tr').forEach(row => {
            row.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('change', () => {
                    const childId = row.querySelector('.btn-save-row').id.replace('btn-', '');
                    const btn = document.getElementById('btn-' + childId);
                    if (btn.innerText === 'Saved!') {
                        btn.innerText = 'Save';
                        btn.style.background = ''; // Revert to original primary color
                    }
                });
                
                // Also for text/time inputs, listen to 'input' event for immediate feedback
                if (input.tagName === 'INPUT') {
                    input.addEventListener('input', () => {
                        const childId = row.querySelector('.btn-save-row').id.replace('btn-', '');
                        const btn = document.getElementById('btn-' + childId);
                        if (btn.innerText === 'Saved!') {
                            btn.innerText = 'Save';
                            btn.style.background = '';
                        }
                    });
                }
            });
        });

        function saveChild(childId) {
            const btn = document.getElementById('btn-' + childId);
            const originalText = 'Save'; // Force back to Save on error
            const originalBg = ''; // Revert to primary
            
            // Show loading state
            btn.innerText = 'Saving...';
            btn.disabled = true;
            btn.style.opacity = '0.7';

            // Gather data
            const date = document.querySelector('input[name="date"]').value;
            const checkIn = document.querySelector(`input[name="attendance[${childId}][check_in_time]"]`).value;
            const checkOut = document.querySelector(`input[name="attendance[${childId}][check_out_time]"]`).value;
            const status = document.querySelector(`select[name="attendance[${childId}][status]"]`).value;
            const notes = document.querySelector(`input[name="attendance[${childId}][notes]"]`).value;

            // Client-side validation: check-in time required for Present/Late
            if ((status === 'present' || status === 'late') && !checkIn) {
                showToast(`Check-in time is required when status is ${status.charAt(0).toUpperCase() + status.slice(1)}.`, 'error');
                resetBtn();
                return;
            }

            // Client-side validation: check-out requires check-in
            if (checkOut && !checkIn) {
                showToast('Check-in time is required before setting check-out time.', 'error');
                resetBtn();
                return;
            }

            // Client-side validation: check-out must be after check-in
            if (checkOut && checkIn && checkOut <= checkIn) {
                showToast('Check-out time must be after check-in time.', 'error');
                resetBtn();
                return;
            }

            // Prepare payload
            const data = {
                date: date,
                attendance: {
                    [childId]: {
                        check_in_time: checkIn,
                        check_out_time: checkOut,
                        status: status,
                        notes: notes
                    }
                }
            };

            // Send AJAX request
            fetch('{{ route("caregiver.attendance.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                   return response.json().then(data => Promise.reject({status: response.status, data: data}));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    btn.innerText = 'Saved!';
                    btn.style.background = '#10b981'; // Green
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    showToast('Attendance saved successfully!', 'success');
                } else {
                    showToast('Error saving data', 'error');
                    resetBtn();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Try to parse validation error response if possible, otherwise generic error
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
                    showToast('Failed to save. Please check input ranges.', 'error');
                }
                resetBtn();
            });

            function resetBtn() {
                btn.innerText = 'Save';
                btn.style.background = '#ef4444'; // Red error
                btn.disabled = false;
                btn.style.opacity = '1';
                
                setTimeout(() => {
                    btn.style.background = originalBg;
                    btn.innerText = originalText;
                }, 2000);
            }
        }
    </script>
@endsection
