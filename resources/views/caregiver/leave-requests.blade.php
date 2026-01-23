@extends('layouts.caregiver')

@section('title', 'Leave Requests')

@section('styles')
    @vite(['resources/css/caregiver/leave-requests.css'])
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
            <h1>Leave Requests</h1>
        </div>
        <div class="top-bar-actions">
            <button class="btn-new-request">
                <i class="fas fa-plus"></i> New Leave Request
            </button>
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
        <!-- Leave Request Form -->

        <div class="card request-form-container">
            <div class="card-header">
                <h3>Submit New Leave Request</h3>
            </div>
            <form action="{{ route('caregiver.leave.store') }}" method="POST" class="form-layout">
                @csrf
                <div class="form-row">
                    <div>
                        <label class="form-label">Leave Type</label>
                        <select name="leave_type" required class="form-select">
                            <option value="">Select leave type</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Personal">Personal</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                   <!-- <div>
                        <label class="form-label">Duration</label>
                        <select name="duration_type" required class="form-select">
                            <option value="Full Day">Full Day</option>
                            <option value="Half Day (Morning)">Half Day (Morning)</option>
                            <option value="Half Day (Afternoon)">Half Day (Afternoon)</option>
                        </select>
                    </div> -->
                </div>
                <div class="form-row">
                    <div>
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" required class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label">Reason <span class="required-asterisk">*</span></label>
                    <textarea name="reason" required rows="4" placeholder="Please provide a reason for your leave request..." class="form-textarea"></textarea>
                </div>
                <div class="form-actions">
                    <button type="reset" class="btn-cancel">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Leave History -->
        <div class="card">
            <div class="card-header">
                <h3>Leave Request History</h3>
                <select class="history-filter-select">
                    <option>All Requests</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $request)
                            <tr>
                                <td>{{ $request->leave_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }}</td>
                                <td>{{ Str::limit($request->reason, 30) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($request->status) }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->status !== 'Pending')
                                        <form action="{{ route('caregiver.leave.delete', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this leave history?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-row" title="Delete History">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">No leave requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const leaveTypeSelect = document.querySelector('select[name="leave_type"]');
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');

            function validateLeaveDuration() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (startDateInput.value && endDateInput.value) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Difference in days
                    
                    // Emergency: Max 1 day (0 difference if start==end)
                    if (leaveTypeSelect.value === 'Emergency') {
                         if (startDateInput.value !== endDateInput.value) {
                            alert('Emergency leave cannot be more than 1 day.');
                            endDateInput.value = startDateInput.value; 
                        }
                    }
                    
                    // Personal: Max 3 days (allow diffDays <= 2 implies 3 days span e.g. 1st, 2nd, 3rd)
                    // Actually, if I pick Jan 1 to Jan 3. 
                    // Jan 3 - Jan 1 = 2 days difference (1st + 2 days = 3rd). Total days = 3.
                    // So diffDays should be <= 2.
                    // If Jan 1 to Jan 4. Diff is 3. Total days = 4. > 3 days.
                    
                    if (leaveTypeSelect.value === 'Personal') {
                        if (diffDays > 2) {
                            alert('Personal leave cannot be more than 3 days.');
                             // Reset to max 3 days from start
                            const maxDate = new Date(startDate);
                            maxDate.setDate(maxDate.getDate() + 2);
                            endDateInput.value = maxDate.toISOString().split('T')[0];
                        }
                    }
                }
            }

            leaveTypeSelect.addEventListener('change', validateLeaveDuration);
            startDateInput.addEventListener('change', validateLeaveDuration);
            endDateInput.addEventListener('change', validateLeaveDuration);

            form.addEventListener('submit', function(event) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDateInput.value && endDateInput.value) {
                     const diffTime = Math.abs(endDate - startDate);
                     const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (leaveTypeSelect.value === 'Emergency') {
                         if (startDateInput.value !== endDateInput.value) {
                            event.preventDefault();
                            alert('Emergency leave cannot be more than 1 day.');
                        }
                    }
                    
                    if (leaveTypeSelect.value === 'Personal') {
                        if (diffDays > 2) {
                            event.preventDefault();
                            alert('Personal leave cannot be more than 3 days.');
                        }
                    }
                }
            });
        });
    </script>
    @endsection
@endsection
