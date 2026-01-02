<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function dashboard()
    {
        return view('caregiver.dashboard');
    }

    public function assignedChildren()
    {
        return view('caregiver.assigned-children');
    }

    public function attendance()
    {
        return view('caregiver.attendance');
    }

    public function dailyReports()
    {
        return view('caregiver.daily-reports');
    }

    public function healthRecords()
    {
        return view('caregiver.health-records');
    }

    public function messages()
    {
        return view('caregiver.messages');
    }

    public function schedule()
    {
        return view('caregiver.schedule');
    }

    public function events()
    {
        return view('caregiver.events');
    }

    public function notifications()
    {
        return view('caregiver.notifications');
    }

    public function leaveRequests()
    {
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('caregiver.leave-requests', compact('leaveRequests'));
    }

    public function storeLeaveRequest(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|string',
            'duration_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        \App\Models\LeaveRequest::create($validated);

        return redirect()->route('caregiver.leave')->with('success', 'Leave request submitted successfully!');
    }
}
