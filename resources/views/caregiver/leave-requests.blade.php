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
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>15</h3>
                    <p>Available Days</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-details">
                    <h3>5</h3>
                    <p>Used Days</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>2</h3>
                    <p>Pending Requests</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-details">
                    <h3>3</h3>
                    <p>Upcoming Leave</p>
                </div>
            </div>
        </div>

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
                            <option value="Vacation">Vacation</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Personal">Personal</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Duration</label>
                        <select name="duration_type" required class="form-select">
                            <option value="Full Day">Full Day</option>
                            <option value="Half Day (Morning)">Half Day (Morning)</option>
                            <option value="Half Day (Afternoon)">Half Day (Afternoon)</option>
                        </select>
                    </div>
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
@endsection
