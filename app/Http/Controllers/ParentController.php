<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            #'dob' => 'nullable|date',
            #'address' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        #$user->dob = $request->dob;
        #$user->address = $request->address;
        $user->save();

        return redirect()->route('parent.settings')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('parent.settings')->with('success', 'Password updated successfully!');
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
