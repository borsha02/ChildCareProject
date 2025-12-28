<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'position' => 'required|string',
            'resume' => 'required|file|mimes:pdf|max:5120', // Max 5MB
        ]);

        // Handle file upload
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validated['resume_path'] = $path;
        }

        // Create application
        JobApplication::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'position' => $validated['position'],
            'resume_path' => $validated['resume_path'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('career')->with('success', 'Application submitted successfully!');
    }
}
