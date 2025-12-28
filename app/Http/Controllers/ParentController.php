<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function dashboard()
    {
        $childrenCount = \App\Models\Child::where('parent_id', auth()->id())->count();
        return view('parent.dashboard', compact('childrenCount'));
    }


    public function childProfile()
    {
        $children = \App\Models\Child::where('parent_id', auth()->id())->get();
        return view('parent.child-profile', compact('children'));
    }

    public function storeChild(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'emergency_contact' => 'required|string|max:255',
            'class' => 'required|in:Toddler,Preschool,Pre-K,Young Learners',
        ]);

        $child = new \App\Models\Child($validated);
        $child->parent_id = auth()->id();
        $child->save();

        return redirect()->route('parent.child-profile')->with('success', 'Child profile created successfully!');
    }

    public function updateChild(Request $request, $id)
    {
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'emergency_contact' => 'required|string|max:255',
            'class' => 'required|in:Toddler,Preschool,Pre-K,Young Learners',
        ]);

        $child->update($validated);

        return redirect()->route('parent.child-profile')->with('success', 'Child profile updated successfully!');
    }

     public function deleteChild($id)
    {
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($id);
        $child->delete();

        return redirect()->route('parent.child-profile')->with('success', 'Child profile deleted successfully!');
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
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->with([
                'vaccinations', 
                'medications', 
                'checkups',
                'healthRecords' => function($query) {
                    $query->orderBy('record_date', 'desc');
                }
            ])
            ->get();
        
        return view('parent.health', compact('children'));
    }

    public function vaccinations()
    {
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->with('vaccinations')
            ->get();
        
        return view('parent.vaccinations', compact('children'));
    }

    public function medications()
    {
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->with('medications')
            ->get();
        
        return view('parent.medications', compact('children'));
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

    public function storeVaccination(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'vaccine_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'administered_date' => 'nullable|date',
            'scheduled_date' => 'nullable|date',
            'status' => 'required|in:completed,upcoming,overdue',
            'notes' => 'nullable|string',
        ]);

        // Verify child belongs to authenticated parent
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($validated['child_id']);

        \App\Models\Vaccination::create($validated);

        return redirect()->route('parent.health')->with('success', 'Vaccination record added successfully!');
    }

    public function storeMedication(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,discontinued',
            'notes' => 'nullable|string',
        ]);

        // Verify child belongs to authenticated parent
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($validated['child_id']);

        \App\Models\Medication::create($validated);

        return redirect()->route('parent.health')->with('success', 'Medication added successfully!');
    }

    public function storeHealthRecord(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'weight' => 'required|numeric|min:0|max:999.99',
            'height' => 'required|numeric|min:0|max:999.99',
            'record_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        // Verify child belongs to authenticated parent
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($validated['child_id']);

        \App\Models\HealthRecord::create($validated);

        return redirect()->route('parent.health')->with('success', 'Health record added successfully!');
    }

    public function updateHealthRecord(Request $request, $id)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0|max:999.99',
            'height' => 'required|numeric|min:0|max:999.99',
            'record_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        // Find the health record and verify it belongs to the parent's child
        $healthRecord = \App\Models\HealthRecord::findOrFail($id);
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($healthRecord->child_id);

        $healthRecord->update($validated);

        return redirect()->route('parent.health')->with('success', 'Health record updated successfully!');
    }

}
