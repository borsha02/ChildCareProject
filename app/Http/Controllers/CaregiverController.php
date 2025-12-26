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
        return view('caregiver.leave-requests');
    }
}
