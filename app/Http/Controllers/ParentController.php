<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function dashboard()
    {
        return view('parent.dashboard');
    }

    public function childProfile()
    {
        return view('parent.child-profile');
    }

    public function reports()
    {
        return view('parent.reports');
    }

    public function attendance()
    {
        return view('parent.attendance');
    }

    public function invoices()
    {
        return view('parent.invoices');
    }

    public function health()
    {
        return view('parent.health');
    }

    public function messages()
    {
        return view('parent.messages');
    }

    public function notifications()
    {
        return view('parent.notifications');
    }

    public function events()
    {
        return view('parent.events');
    }

    public function settings()
    {
        return view('parent.settings');
    }

    public function help()
    {
        return view('parent.help');
    }

    public function caregivers()
    {
        return view('parent.caregivers');
    }
}
