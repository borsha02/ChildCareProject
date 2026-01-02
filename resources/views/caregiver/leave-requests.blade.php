@extends('layouts.caregiver')

@section('title', 'Leave Requests')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Leave Requests</h1>
        <div class="top-bar-actions">
            <button
                style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
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
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">
                <h3>Submit New Leave Request</h3>
            </div>
            <form action="{{ route('caregiver.leave.store') }}" method="POST" style="display: grid; gap: 20px;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Leave
                            Type</label>
                        <select name="leave_type" required
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            <option value="">Select leave type</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Personal">Personal</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label
                            style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Duration</label>
                        <select name="duration_type" required
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            <option value="Full Day">Full Day</option>
                            <option value="Half Day (Morning)">Half Day (Morning)</option>
                            <option value="Half Day (Afternoon)">Half Day (Afternoon)</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Start
                            Date</label>
                        <input type="date" name="start_date" required
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">End
                            Date</label>
                        <input type="date" name="end_date" required
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Reason <span
                            style="color: #ef4444;">*</span></label>
                    <textarea name="reason" required rows="4" placeholder="Please provide a reason for your leave request..."
                        style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="reset"
                        style="padding: 12px 24px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        Cancel
                    </button>
                    <button type="submit"
                        style="padding: 12px 24px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Leave History -->
        <div class="card">
            <div class="card-header">
                <h3>Leave Request History</h3>
                <select style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    <option>All Requests</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                </select>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Type</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Start Date</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">End Date</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Days</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Reason</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #1f2937;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $request)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 12px; color: #4b5563;">{{ $request->leave_type }}</td>
                                <td style="padding: 12px; color: #4b5563;">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</td>
                                <td style="padding: 12px; color: #4b5563;">
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</td>
                                <td style="padding: 12px; color: #4b5563;">
                                    {{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }}
                                </td>
                                <td style="padding: 12px; color: #4b5563;">{{ Str::limit($request->reason, 30) }}</td>
                                <td style="padding: 12px;">
                                    @php
                                        $statusColors = [
                                            'pending' => 'background: #fef3c7; color: #92400e;',
                                            'approved' => 'background: #d1fae5; color: #065f46;',
                                            'rejected' => 'background: #fee2e2; color: #991b1b;',
                                        ];
                                        $style = $statusColors[$request->status] ?? $statusColors['pending'];
                                    @endphp
                                    <span
                                        style="padding: 6px 12px; {{ $style }} border-radius: 20px; font-size: 12px; font-weight: 600;">{{ ucfirst($request->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 20px; text-align: center; color: #6b7280;">No leave
                                    requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
