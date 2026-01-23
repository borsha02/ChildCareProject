<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Child;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffWelcomeMail;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with system-wide statistics
     * Feature #9: Analytics Dashboard
     */
    public function dashboard()
    {
        // Get system-wide statistics with error handling
        try {
            $pendingApplications = \App\Models\JobApplication::where('status', 'pending')->count();
            $pendingRegistrations = \App\Models\Child::where('status', 'pending')->count();
            $pendingLeaveRequests = \App\Models\LeaveRequest::where('status', 'pending')->count();

            $stats = [
                'total_users' => User::count(),
                'total_parents' => User::where('role', 'parent')->count(),
                'total_staff' => User::where('role', 'caregiver')->count(),
                'total_children' => \App\Models\Child::where('status', '!=', 'pending')->count(), 
                'active_today' => DB::table('attendances')->whereDate('date', today())->whereIn('status', ['present', 'late'])->count(),
                'pending_payments' => \App\Models\Invoice::where('status', 'pending')->sum('amount'),
                'total_revenue' => \App\Models\Payment::whereIn('status', ['Approved', 'Completed'])->sum('amount'),
                'pending_approvals' => 0, // Reset to 0 as we use specific badges now
                'pending_applications' => $pendingApplications,
                'pending_registrations' => $pendingRegistrations,
                'pending_leave_requests' => $pendingLeaveRequests,
            ];
        } catch (\Exception $e) {
            // If database connection fails, use default values
            $stats = [
                'total_users' => 0,
                'total_parents' => 0,
                'total_staff' => 0,
                'total_children' => 0,
                'active_today' => 0,
                'pending_payments' => 0,
                'total_revenue' => 0,
                'pending_approvals' => 0,
                'pending_applications' => 0,
                'pending_registrations' => 0,
            ];
        }

        // Recent activities aggregation
        $recentUsers = User::latest()->take(5)->get()->map(function ($user) {
            return [
                'type' => 'user',
                'title' => 'New User Registered',
                'description' => $user->name . ' (' . ucfirst($user->role) . ') joined',
                'time' => $user->created_at,
                'icon' => 'fas fa-user-plus',
                'color' => 'user', // css class for color
                'link' => route('admin.users')
            ];
        });

        $recentChildren = \App\Models\Child::latest()->take(5)->get()->map(function ($child) {
            return [
                'type' => 'child',
                'title' => 'Child Registration',
                'description' => $child->first_name . ' ' . $child->last_name . ' registered',
                'time' => $child->created_at,
                'icon' => 'fas fa-child',
                'color' => 'success', // reusing payment/success color class or add new
                'link' => route('admin.children')
            ];
        });

        $recentApplications = \App\Models\JobApplication::latest()->take(5)->get()->map(function ($app) {
            return [
                'type' => 'application',
                'title' => 'Job Application',
                'description' => $app->full_name . ' applied for ' . $app->position,
                'time' => $app->created_at,
                'icon' => 'fas fa-briefcase',
                'color' => 'orange', // orange/alert color
                'link' => route('admin.job-applications')
            ];
        });

        // Merge, sort by time desc, and take top 5
        $recentActivities = $recentUsers->merge($recentChildren)
            ->merge($recentApplications)
            ->sortByDesc('time')
            ->take(5);

        // Upcoming events (placeholder for now)
        $upcomingEvents = [];

        return view('admin.dashboard', compact('stats', 'recentActivities', 'upcomingEvents'));
    }

    /**
     * Feature #2: Manage Users - Display all users
     * Feature #3: Assign Roles - View and manage user roles
     */
    public function users()
    {
        $users = User::with('children')->orderBy('created_at', 'desc')->paginate(20);
        $enrolledChildren = Child::where('status', '!=', 'pending')->with(['parent', 'caregivers'])->latest()->get();

        return view('admin.users', compact('users', 'enrolledChildren'));
    }

    /**
     * Feature #2: Create new user
     */
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,caregiver,parent',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    /**
     * Feature #2: Update user details
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,caregiver,parent',
        ];

        // Only validate password if it's being updated
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Remove password from validated if it's not set (handled by request->filled check but good for safety)
        if (!$request->filled('password')) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Feature #2: Toggle user status (Active <-> Inactive)
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'User activated successfully!' : 'User deactivated successfully!';

        return redirect()->route('admin.users')
            ->with('success', $message);
    }

    /**
     * Feature #3: Assign role to user
     */
    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|in:admin,caregiver,parent',
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin.users')
            ->with('success', 'Role assigned successfully!');
    }

    /**
     * Feature #4: Manage Child Records
     */
    public function children(Request $request)
    {
        $search = $request->input('search');
        $classFilter = $request->input('class');

        $query = Child::with('parent');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereHas('parent', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($classFilter) {
            $query->where('class', $classFilter);
        }

        // Separate pending and enrolled (active/inactive)
        // Pending: status = 'pending'
        // Enrolled: status != 'pending' (includes active, inactive, rejected? maybe filter rejected)
        
        $pendingChildren = (clone $query)->where('status', 'pending')->latest()->get();
        // For enrolled, we might show active and inactive. Rejected are usually hidden or in a separate view, 
        // but for now let's just say != pending.
        $enrolledChildren = (clone $query)->where('status', '!=', 'pending')->with('caregivers')->latest()->paginate(10);
        $caregivers = User::where('role', 'caregiver')->where('status', 'active')->get();

        return view('admin.children', compact('pendingChildren', 'enrolledChildren', 'caregivers'));
    }

    public function assignCaregiver(Request $request, $id)
    {
        $child = Child::findOrFail($id);
        $validated = $request->validate([
            'caregiver_id' => 'required|exists:users,id',
        ]);
        
        $caregiver = User::findOrFail($validated['caregiver_id']);

        // Check if caregiver is already assigned to a different class
        // We look at all children this caregiver is assigned to
        // If they have distinct classes that are different from the current child's class, we block it.
        // NOTE: Usually a caregiver should only have ONE class.
        // So we just check if they have ANY child with a class != $child->class
        
        $assignedClasses = $caregiver->assignedChildren()
            ->where('class', '!=', $child->class)
            ->pluck('class')
            ->unique();

        if ($assignedClasses->isNotEmpty()) {
            $conflictClass = $assignedClasses->first();
            return redirect()->back()->with('error', "Caregiver is already assigned to class '{$conflictClass}'. Please remove them from that class before assigning to '{$child->class}'.")->withFragment('enrolled');
        }

        // Check max assignment limit (Max 2 children per caregiver)
        if ($caregiver->assignedChildren()->count() >= 2) {
             return redirect()->back()->with('error', "Caregiver limit reached. This caregiver is already assigned to 2 children.")->withFragment('enrolled');
        }

        // Check if child already has a full-time caregiver
        $existingFullTime = $child->caregivers()->where('shift', 'full-time')->first();
        if ($existingFullTime) {
            return redirect()->back()->with('error', "This child is already assigned to a Full-Time caregiver (" . $existingFullTime->name . "). No other caregivers can be assigned.")->withFragment('enrolled');
        }

        // If assigning a full-time caregiver, check if anyone else is assigned
        if ($caregiver->shift === 'full-time' && $child->caregivers()->count() > 0) {
             return redirect()->back()->with('error', "Cannot assign a Full-Time caregiver because this child already has other caregivers assigned.")->withFragment('enrolled');
        }

        // Check if child already has a caregiver for this shift (for non-full-time)
        if ($caregiver->shift && $caregiver->shift !== 'full-time') {
            $existingShiftCaregiver = $child->caregivers()->where('shift', $caregiver->shift)->first();
            if ($existingShiftCaregiver) {
                return redirect()->back()->with('error', "This child already has a " . ucfirst($caregiver->shift) . " shift caregiver assigned (" . $existingShiftCaregiver->name . ").")->withFragment('enrolled');
            }
        }

        // Check if already assigned to this specific child
        if (!$child->caregivers->contains($validated['caregiver_id'])) {
            $child->caregivers()->attach($validated['caregiver_id']);
             return redirect()->back()->with('success', 'Caregiver assigned successfully.')->withFragment('enrolled');
        }

        return redirect()->back()->with('warning', 'Caregiver already assigned.')->withFragment('enrolled');
    }

    public function removeCaregiver(Request $request, $id)
    {
        $child = Child::findOrFail($id);
        $validated = $request->validate([
            'caregiver_id' => 'required|exists:users,id',
        ]);
        
        $child->caregivers()->detach($validated['caregiver_id']);
        return redirect()->back()->with('success', 'Caregiver removed successfully.')->withFragment('enrolled');
    }

    public function approveChild($id)
    {
        $child = Child::with('parent')->findOrFail($id);
        $child->update(['status' => 'active']);
        
        if ($child->parent) {
            $child->parent->notify(new \App\Notifications\ChildApproved($child));
        }

        return redirect()->back()->with('success', 'Child registration approved successfully.')->withFragment('enrolled');
    }

    public function rejectChild($id)
    {
        $child = Child::findOrFail($id);
        $child->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Child registration rejected.')->withFragment('requests');
    }

    public function createChild(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'enrollment_date' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'class' => 'required|in:Toddler,Preschool,Pre-K,Young Learners',
            'package' => 'required|in:monthly,weekly',
            'parent_email' => 'required|email',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'medical_info' => 'nullable|string',
            'caregiver_id' => 'nullable|exists:users,id',
        ]);

        $parent = User::where('email', $validated['parent_email'])->first();

        if (!$parent) {
            $parent = User::create([
                'name' => $validated['parent_name'],
                'email' => $validated['parent_email'],
                'phone' => $validated['parent_phone'],
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'status' => 'active',
            ]);
        }

        $child = Child::create([
            'parent_id' => $parent->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'dob' => $validated['dob'],
            'enrollment_date' => $validated['enrollment_date'] ?? now(),
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'class' => $validated['class'],
            'package' => $validated['package'],
            'emergency_contact' => $validated['parent_phone'],
            'medical_notes' => $validated['medical_info'] ?? null,
            'status' => 'active', // Admin created children are auto-approved
        ]);

        if ($request->filled('caregiver_id')) {
            $child->caregivers()->attach($request->caregiver_id);
        }

        return redirect()->route('admin.children')->with('success', 'Child record created successfully!')->withFragment('enrolled');
    }

    public function updateChild(Request $request, $id)
    {
        $child = Child::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'enrollment_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'class' => 'required|string',
            'package' => 'required|in:monthly,weekly',
            'medical_info' => 'nullable|string',
        ]);

        $child->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'dob' => $validated['dob'],
            'enrollment_date' => $validated['enrollment_date'],
            'gender' => $validated['gender'],
            'class' => $validated['class'],
            'package' => $validated['package'],
            'medical_notes' => $validated['medical_info'] ?? $child->medical_notes,
        ]);

        return redirect()->route('admin.children')->with('success', 'Child record updated successfully!')->withFragment('enrolled');
    }

    public function reactivate($id)
    {
        $child = \App\Models\Child::findOrFail($id);
        
        $child->status = 'active';
        $child->enrollment_date = now(); // Reset enrollment date to today
        $child->save();

        return redirect()->route('admin.children')->with('success', 'Child reactivated successfully. Enrollment date reset to today.')->withFragment('enrolled');
    }

    public function deleteChild($id)
    {
        $child = Child::findOrFail($id);
        $child->delete();

        return redirect()->back()->with('success', 'Child record deleted successfully!');
    }


    /**
     * Feature #5: Manage Staff
     */
    public function staff()
    {
        $staff = User::where('role', 'caregiver')
            ->withAvg('ratings', 'rating')
            ->with('assignedChildren')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Job applications moved to dedicated page
        $leaveRequests = LeaveRequest::where('status', 'pending')->with('user')->orderBy('created_at', 'asc')->get();

        return view('admin.staff', compact('staff', 'leaveRequests'));
    }

    public function jobApplications()
    {
        $jobApplications = \App\Models\JobApplication::orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.job-applications', compact('jobApplications'));
    }

    public function deleteJobApplication($id)
    {
        $application = \App\Models\JobApplication::findOrFail($id);
        $application->delete();

        return redirect()->back()->with('success', 'Job application deleted successfully.');
    }

    public function approveLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }

    public function denyLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Leave request denied.');
    }

    /**
     * Feature #5: Add staff member
     */
    public function createStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'nullable|string|max:255',
            'shift' => 'nullable|in:morning,afternoon,evening,full-time',
            'application_id' => 'nullable|exists:job_applications,id',
        ]);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'caregiver',
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'specialization' => $validated['specialization'] ?? null,
            'shift' => $validated['shift'] ?? null,
        ]);

        // If this was from a job application, update its status
        if ($request->has('application_id')) {
            $application = JobApplication::find($request->application_id);
            if ($application) {
                $application->update(['status' => 'approved']);
            }
        }

        // Send welcome email
        try {
            Mail::to($staff->email)->send(new StaffWelcomeMail($staff, $validated['password']));
        } catch (\Exception $e) {
            // Log error or just continue, we don't want to break the flow if mail fails
            // \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->route('admin.staff')
            ->with('success', 'Staff member added successfully! Welcome email sent.');
    }

    /**
     * Feature #5: Reject job application
     */
    public function rejectJobApplication($id)
    {
        $application = JobApplication::findOrFail($id);
        
        // Optional: Delete resume file if needed, but primary request is database deletion
        // if ($application->resume_path) { Storage::delete($application->resume_path); }

        $application->delete();

        return redirect()->back()
            ->with('success', 'Job application deleted successfully.');
    }

    /**
     * Feature #5: Update staff details
     */
    public function updateStaff(Request $request, $id)
    {
        $staff = User::where('role', 'caregiver')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'shift' => 'nullable|in:morning,afternoon,evening,full-time',
        ]);

        $staff->update($validated);

        // Reload relationships to ensure fresh data
        $staff->load('assignedChildren');
        
        \Log::info("UpdateStaff: Staff {$staff->id} updated. Shift: {$staff->shift}. Assigned Children Count: " . $staff->assignedChildren->count());

        // Check for conflicts in assigned children
        $removedCount = 0;
        foreach ($staff->assignedChildren as $child) {
            $conflict = false;
            
            // Debug logging
            \Log::info("Checking conflict for Staff: {$staff->id} ({$staff->shift}), Child: {$child->id}");

            if ($staff->shift === 'full-time') {
                if ($child->caregivers()->where('users.id', '!=', $staff->id)->exists()) {
                    $conflict = true;
                    \Log::info("Conflict detected: Child has other caregivers when switching to full-time.");
                }
            } else {
                // If now morning/evening
                $fullTimeExists = $child->caregivers()->where('users.id', '!=', $staff->id)->where('shift', 'full-time')->exists();
                $sameShiftExists = $child->caregivers()->where('users.id', '!=', $staff->id)->where('shift', $staff->shift)->exists();
                
                \Log::info("FullTimeExists: " . ($fullTimeExists ? 'yes' : 'no'));
                \Log::info("SameShiftExists: " . ($sameShiftExists ? 'yes' : 'no') . " (Looking for shift: {$staff->shift})");

                if ($fullTimeExists) {
                    $conflict = true;
                } elseif ($sameShiftExists) {
                    $conflict = true;
                }
            }

            if ($conflict) {
                \Log::info("Detaching staff {$staff->id} from child {$child->id}");
                $child->caregivers()->detach($staff->id);
                $removedCount++;
            }
        }

        $message = 'Staff details updated successfully!';
        if ($removedCount > 0) {
            $message .= " Note: Removed from ({$removedCount}) children due to shift conflicts.";
        }

        return redirect()->route('admin.staff')
            ->with('success', $message);
    }

    /**
     * Feature #5: Delete staff member
     */
    public function deleteStaff($id)
    {
        $staff = User::where('role', 'caregiver')->findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff')
            ->with('success', 'Staff member deleted successfully!');
    }

    /**
     * Feature #5: Assign staff to child
     */
    public function assignStaffToChild(Request $request)
    {
        // Will be implemented when Child and StaffAssignment models are created
        return redirect()->back()
            ->with('success', 'Staff assigned to child successfully!');
    }

    public function updateAttendance(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:children,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'check_in_time' => [
                'nullable', 
                function ($attribute, $value, $fail) use ($request) {
                    $status = $request->input('status');
                    if (in_array($status, ['present', 'late']) && empty($value)) {
                        $fail('Check-in time is required when status is ' . ucfirst($status) . '.');
                    }
                    if ($value && ($value < '08:00' || $value > '18:00')) {
                        $fail('Check-in time must be between 08:00 AM and 06:00 PM.');
                    }
                },
            ],
            'check_out_time' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && ($value < '08:00' || $value > '18:30')) {
                        $fail('Check-out time must be between 08:00 AM and 06:30 PM.');
                    }
                    
                    $checkInTime = $request->input('check_in_time');
                    if ($value && empty($checkInTime)) {
                        $fail('Check-in time is required before setting check-out time.');
                    }
                    
                    if ($value && $checkInTime && $value <= $checkInTime) {
                        $fail('Check-out time must be after check-in time.');
                    }
                },
            ],
            'notes' => 'nullable|string|max:255',
        ]);

        // Fix: Use explicit whereDate and handle potential duplicates
        $attendances = \App\Models\Attendance::where('child_id', $request->child_id)
            ->whereDate('date', $request->date)
            ->get();

        if ($attendances->count() > 0) {
            // Use the first record as the primary one to update
            $attendance = $attendances->first();
            
            // Delete any duplicates if they exist (cleanup)
            if ($attendances->count() > 1) {
                $attendances->slice(1)->each(function($duplicate) {
                    $duplicate->delete();
                });
            }

            $attendance->status = $request->status;
            $attendance->check_in_time = $request->check_in_time;
            $attendance->check_out_time = $request->check_out_time;
            $attendance->caregiver_id = auth()->id();
            $attendance->notes = $request->notes;
            $attendance->save();
        } else {
            \App\Models\Attendance::create([
                'child_id' => $request->child_id,
                'date' => $request->date,
                'status' => $request->status,
                'check_in_time' => $request->check_in_time,
                'check_out_time' => $request->check_out_time,
                'caregiver_id' => auth()->id(),
                'notes' => $request->notes,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Attendance updated successfully.']);
    }

    /**
     * Feature #5: View staff ratings and reviews
     */
    public function staffRatings($id)
    {
        $staff = User::where('role', 'caregiver')->findOrFail($id);
        // Placeholder for ratings - will be implemented when Rating model is created
        $ratings = [];

        return view('admin.staff-ratings', compact('staff', 'ratings'));
    }

    public function ratings()
    {
        $ratings = \App\Models\Rating::with(['parent', 'caregiver'])->latest()->get();
        
        // Fetch stats for sidebar
        try {
            $pendingApplications = \App\Models\JobApplication::where('status', 'pending')->count();
            $pendingRegistrations = \App\Models\Child::where('status', 'pending')->count();
            $pendingLeaveRequests = \App\Models\LeaveRequest::where('status', 'pending')->count();

            $stats = [
                'pending_registrations' => $pendingRegistrations,
                'pending_payments' => 0, // Placeholder
                'pending_applications' => $pendingApplications,
                'pending_leave_requests' => $pendingLeaveRequests,
            ];
            $pendingJobAppsCount = $pendingApplications;
        } catch (\Exception $e) {
            $stats = [];
            $pendingJobAppsCount = 0;
        }

        return view('admin.ratings', compact('ratings', 'stats', 'pendingJobAppsCount'));
    }

    /**
     * Feature #6: Monitor Attendance
     */
    public function attendance(\Illuminate\Http\Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        // Fetch all enrolled children with their attendance for the specific date
        $children = \App\Models\Child::where('status', '!=', 'pending')
            ->with(['attendances' => function($query) use ($date) {
                $query->whereDate('date', $date)->latest('updated_at'); // Ensure we get the latest update if dups exist
            }, 'parent'])
            ->orderBy('first_name')
            ->get();

        // Calculate stats
        $totalChildren = $children->count();
        $present = 0;
        $late = 0;
        $absent = 0;

        foreach ($children as $child) {
            $attendance = $child->attendances->first();
            if ($attendance) {
                if ($attendance->status === 'late') {
                    $late++;
                    $present++; // Late counts as present usually, or handle separately. Let's count as present for "Present Today" stat
                } elseif ($attendance->status === 'present') {
                    $present++;
                } else {
                    $absent++; // explicitly marked absent
                }
            } else {
                $absent++; // No record = absent
            }
        }

        // Attendance Rate
        $attendanceRate = $totalChildren > 0 ? round(($present / $totalChildren) * 100) : 0;

        $stats = [
            'present_today' => $present,
            'absent_today' => $absent,
            'late_today' => $late,
            'attendance_rate' => $attendanceRate,
        ];

        return view('admin.attendance', compact('children', 'stats', 'date'));
    }

    /**
     * Feature #6: Export attendance reports
     */
    public function exportAttendance(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $filename = "attendance_report_{$date}.csv";

        $children = \App\Models\Child::where('status', '!=', 'pending')
            ->with(['attendances' => function($query) use ($date) {
                $query->whereDate('date', $date);
            }])
            ->orderBy('class')
            ->orderBy('first_name')
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Child Name', 'ID', 'Class', 'Status', 'Check In', 'Check Out', 'Notes');

        $callback = function() use($children, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($children as $child) {
                $attendance = $child->attendances->first();
                
                $status = $attendance ? ucfirst($attendance->status) : 'Absent';
                $checkIn = $attendance && $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('g:i A') : '-';
                $checkOut = $attendance && $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('g:i A') : '-';
                $notes = $attendance ? $attendance->notes : '';

                $row = array(
                    $child->first_name . ' ' . $child->last_name,
                    'CH' . str_pad($child->id, 3, '0', STR_PAD_LEFT),
                    $child->class,
                    $status,
                    $checkIn,
                    $checkOut,
                    $notes
                );

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Feature #7: Daily Reports - Monitor daily activity logs
     */
    public function reports(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        // Fetch reports for the selected date
        $dailyReports = \App\Models\DailyReport::whereDate('report_date', $date)
            ->with(['child', 'caregiver'])
            ->latest()
            ->get();

        // Fetch all children for filter (active only)
        $children = \App\Models\Child::where('status', '!=', 'pending')
            ->orderBy('first_name')
            ->get();

        return view('admin.reports', compact('dailyReports', 'children', 'date'));
    }

    public function viewDailyReport($id)
    {
        $report = \App\Models\DailyReport::with(['child', 'caregiver'])->findOrFail($id);
        return response()->json($report);
    }

    public function exportReportsPdf(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        
        $dailyReports = \App\Models\DailyReport::whereDate('report_date', $date)
            ->with(['child', 'caregiver'])
            ->join('children', 'daily_reports.child_id', '=', 'children.id')
            ->orderBy('children.first_name')
            ->select('daily_reports.*')
            ->get();

        return view('admin.reports-pdf', compact('dailyReports', 'date'));
    }



    /**
     * Feature #8: Billing & Invoices
     */
    public function invoices()
    {
        $invoices = \App\Models\Invoice::with(['parent', 'child'])->latest()->get();
        // Fetch children with parents for the generation modal
        $children = \App\Models\Child::with('parent')->where('status', '!=', 'pending')->get();

        // Process children to identify siblings for discount eligibility
        // Logic: Group by parent. First child (by id/enrollment) pays full. Subsequent pay discounted.
        $children = $children->map(function ($child) {
            // Get all children of this parent, sorted by ID (assuming lower ID = older/first enrolled)
            $siblings = \App\Models\Child::where('parent_id', $child->parent_id)
                ->where('status', '!=', 'pending')
                ->orderBy('id')
                ->get();
            
            // Check if this child is the first one
            $isFirstChild = $siblings->first()->id === $child->id;
            
            // If NOT first child, they are a "sibling" eligible for discount
            $child->is_sibling = !$isFirstChild;
            
            return $child;
        });

        $stats = [
            'total_invoices' => $invoices->count(),
            'paid_invoices' => $invoices->where('status', 'paid')->count(),
            'pending_invoices' => $invoices->where('status', 'pending')->count(),
            'overdue_invoices' => $invoices->where('status', 'overdue')->count(),
            'total_revenue' => $invoices->where('status', 'paid')->sum('amount'), // Sum of paid invoices
            'collected_revenue' => $invoices->where('status', 'paid')->sum('amount'), // Explicitly collected
            'outstanding_amount' => $invoices->where('status', 'pending')->sum('amount'),
            'overdue_amount' => $invoices->where('status', 'overdue')->sum('amount'),
        ];

        // Fetch fees for JS auto-fill
        $fees = [
            'weekly' => \App\Models\AdminSetting::where('key', 'weekly_fee')->value('value') ?? 0,
            'monthly' => \App\Models\AdminSetting::where('key', 'monthly_fee')->value('value') ?? 0,
            'sibling_discount' => \App\Models\AdminSetting::where('key', 'sibling_discount')->value('value') ?? 0,
        ];

        return view('admin.invoices', compact('invoices', 'stats', 'children', 'fees'));
    }

    /**
     * Feature #8: Generate invoice
     */
    /**
     * Feature #8: Generate invoice
     */
    public function generateInvoice(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        $child = \App\Models\Child::findOrFail($validated['child_id']);

        // Generate unique invoice number
        $invoiceNumber = 'INV-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        \App\Models\Invoice::create([
            'invoice_number' => $invoiceNumber,
            'parent_id' => $child->parent_id, // Automatically link to the child's parent
            'child_id' => $child->id,
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.invoices')
            ->with('success', 'Invoice generated successfully!');
    }

    /**
     * Feature #8: Update invoice
     */
    /**
     * Feature #8: Update invoice
     */
    public function updateInvoice(Request $request, $id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        $invoice->update([
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.invoices')
            ->with('success', 'Invoice updated successfully!');
    }

    /**
     * Feature #8: Download invoice
     */
    public function downloadInvoice($id)
    {
        $invoice = \App\Models\Invoice::with(['parent', 'child'])->findOrFail($id);
        // specific view for printing/downloading
        return view('admin.invoice-pdf', compact('invoice'));
    }

    /**
     * Feature #9: Analytics Dashboard
     */
    public function analytics(Request $request)
    {
        $period = $request->input('period', '7_days');
        
        // Determine date range based on period
        $startDate = now();
        switch($period) {
            case '7_days': $startDate = now()->subDays(7); break;
            case '30_days': $startDate = now()->subDays(30); break;
            case '3_months': $startDate = now()->subMonths(3); break;
            case '1_year': $startDate = now()->subYear(); break;
            default: $startDate = now()->subDays(7); break;
        }

        // 1. Enrollment Statistics (Snapshots are hard without history table, so we stick to current)
        // We could filter 'newThisMonth' based on the period though
        $totalEnrolled = \App\Models\Child::count();
        $activeChildren = \App\Models\Child::where('status', 'active')->count();
        $inactiveChildren = \App\Models\Child::where('status', 'inactive')->count();
        
        // New registrations in the selected period
        $newRegistrations = \App\Models\Child::where('created_at', '>=', $startDate)->count();

        // 2. Revenue Statistics (In the selected period)
        $totalRevenue = \App\Models\Payment::whereIn('status', ['Approved', 'Completed'])
            ->where('updated_at', '>=', $startDate)
            ->sum('amount');
        
        // Monthly Revenue Chart Data (Always show last 6 months context, or adjust to period?)
        // Let's keep the chart logic showing "Context" (last 6 months) but the Stat Card shows "Total Revenue" for the *period*.
        $paymentsLabels = [];
        $paymentsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $paymentsLabels[] = $date->format('M');
            $paymentsData[] = \App\Models\Payment::whereIn('status', ['Approved', 'Completed'])
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->sum('amount');
        }

        // 3. Attendance Statistics 
        // Showing attendance trend for the period would be complex if period is 1 year. 
        // Let's keep the chart as "Recent Weekly Trend" (last 7 days) regardless of large filters, 
        // OR if filter is 30_days, show 4 weeks?
        // For simplicity while maintaining UI: Keep Chart as Daily bars.
        // If period > 7 days, maybe show last 7 days still in chart? 
        // Let's stick to showing Last 7 days in the chart for readability on this specific view card
        // But the "Avg Attendance" metric card will reflect the PERIOD.
        
        $attendanceLabels = [];
        $attendanceData = []; 
        for ($i = 6; $i >= 0; $i--) { // Last 7 days including today
            $date = now()->subDays($i);
            $attendanceLabels[] = $date->format('D');
            
            $totalActive = $activeChildren > 0 ? $activeChildren : 1; 
            $presentCount = DB::table('attendances')
                ->whereDate('date', $date->format('Y-m-d'))
                ->whereIn('status', ['present', 'late'])
                ->count();
            
            $attendanceData[] = round(($presentCount / $totalActive) * 100);
        }

        // Calculate Average Attendance Rate over the selected PERIOD (not just chart days)
        // This query gets avg rate over the full $startDate period
        // Optimization: Get count of all attendance records in period / (active children * days)
        // Estimation: Average of daily percentages
        $periodDays = now()->diffInDays($startDate);
        $periodDays = $periodDays < 1 ? 1 : $periodDays;
        
        $totalPresentInPeriod = DB::table('attendances')
            ->whereDate('date', '>=', $startDate)
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        // Approximation: Total present / (Active Children * Days)
        // This assumes active children count was constant. It's an estimate.
        $denominator = ($activeChildren * $periodDays);
        $avgAttendanceRate = $denominator > 0 ? round(($totalPresentInPeriod / $denominator) * 100) : 0;
        
        // Cap at 100 just in case
        $avgAttendanceRate = min($avgAttendanceRate, 100);


        // 4. Feedback / Ratings (All time or Period?)
        // Let's do Period for counting "New" ratings, but "Distribution" usually implies "Current Sentiment" (All time).
        // Let's keep Distribution as "All Time" because dropping to 0 for "7 days" might look empty.
        // But the "Parent Satisfaction" text says "5% increase", implying trend.
        // Let's just calculate All Time for the Pie Chart.
        $positiveRatings = \App\Models\Rating::where('rating', '>=', 4)->count();
        $neutralRatings = \App\Models\Rating::where('rating', 3)->count();
        $negativeRatings = \App\Models\Rating::where('rating', '<', 3)->count();
        $totalRatings = $positiveRatings + $neutralRatings + $negativeRatings;
        
        $feedbackPositivePct = $totalRatings > 0 ? round(($positiveRatings / $totalRatings) * 100) : 0;
        $feedbackNeutralPct = $totalRatings > 0 ? round(($neutralRatings / $totalRatings) * 100) : 0;
        $feedbackNegativePct = $totalRatings > 0 ? round(($negativeRatings / $totalRatings) * 100) : 0;


        $analytics = [
            'period' => $period, // Pass back to view
            'attendance' => [
                'labels' => $attendanceLabels,
                'data' => $attendanceData,
                'average' => $avgAttendanceRate,
            ],
            'payments' => [
                'labels' => $paymentsLabels,
                'data' => $paymentsData,
                'total_period' => $totalRevenue,
                'total_life' => \App\Models\Payment::whereIn('status', ['Approved', 'Completed'])->sum('amount'),
            ],
            'feedback' => [
                'positive' => $feedbackPositivePct,
                'neutral' => $feedbackNeutralPct,
                'negative' => $feedbackNegativePct,
            ],
            'enrollment' => [
                'total' => $totalEnrolled,
                'new_period' => $newRegistrations, // Renamed from new_this_month
                'active' => $activeChildren,
                'inactive' => $inactiveChildren,
            ],
        ];

        return view('admin.analytics', compact('analytics'));
    }





    /**
     * Feature #11: Approve Payments
     */
    /**
     * Feature #11: Approve Payments
     */
    public function pendingPayments()
    {
        // Fetch all payments for the list
        $pendingPayments = \App\Models\Payment::with(['invoice.parent', 'invoice.child'])
            ->latest()
            ->get();

        // Calculate Stats
        
        // 1. Pending Approvals (Status is not Approved or Rejected)
        // Adjust status check based on your specific logic. If 'Completed' means pending admin review, use that.
        // Assuming 'Completed' from SSLCommerz needs Admin Approval to move to 'Approved'
        // Or if 'Pending' is the status for manual payments. 
        // Let's assume anything NOT 'Approved' or 'Rejected' is pending review.
        $pendingCount = \App\Models\Payment::whereNotIn('status', ['Approved', 'Rejected'])->count();

        // 2. Approved Today
        $approvedToday = \App\Models\Payment::where('status', 'Approved')
            ->whereDate('updated_at', today())
            ->get();
        $approvedTodayCount = $approvedToday->count();
        $approvedTodayAmount = $approvedToday->sum('amount');

        // 3. Rejected This Week
        $rejectedWeekCount = \App\Models\Payment::where('status', 'Rejected')
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // 4. Total Processed This Month (Approved or Rejected)
        $totalProcessedMonthCount = \App\Models\Payment::whereIn('status', ['Approved', 'Rejected'])
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        return view('admin.pending-payments', compact(
            'pendingPayments',
            'pendingCount',
            'approvedTodayCount',
            'approvedTodayAmount',
            'rejectedWeekCount',
            'totalProcessedMonthCount'
        ));
    }

    /**
     * Feature #11: Approve payment
     */
    public function approvePayment($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $payment->update(['status' => 'Approved']);
        
        // Update associated invoice status
        if ($payment->invoice) {
            $payment->invoice->update(['status' => 'paid']);
        }
        
        return redirect()->back()
            ->with('success', 'Payment approved successfully!');
    }

    /**
     * Feature #11: Reject payment
     */
    public function rejectPayment(Request $request, $id)
    {
        // Reason is optional for now as manual payments might not have strictly defined rejection flows
        /*
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);
        */

        $payment = \App\Models\Payment::findOrFail($id);
        $payment->update(['status' => 'Rejected']);
        
        // If rejected, we might want to set invoice back to pending?
        // For now, let's keep it simple. Only change payment status.
        // If needed, we can: $payment->invoice->update(['status' => 'pending']);

        return redirect()->back()
            ->with('success', 'Payment rejected!');
    }



    /**
     * Feature #13: Manage Configurations
     */
    public function settings()
    {
        // Get system configurations from DB
        $settings = [
            'fees' => [
                'weekly_fee' => \App\Models\AdminSetting::where('key', 'weekly_fee')->value('value') ?? 0,
                'monthly_fee' => \App\Models\AdminSetting::where('key', 'monthly_fee')->value('value') ?? 0,
                'sibling_discount' => \App\Models\AdminSetting::where('key', 'sibling_discount')->value('value') ?? 0,
            ],
            'timings' => [
                'opening_time' => \App\Models\AdminSetting::where('key', 'opening_time')->value('value') ?? '07:00',
                'closing_time' => \App\Models\AdminSetting::where('key', 'closing_time')->value('value') ?? '18:00',
                'breakfast_time' => \App\Models\AdminSetting::where('key', 'breakfast_time')->value('value') ?? '08:00',
                'lunch_time' => \App\Models\AdminSetting::where('key', 'lunch_time')->value('value') ?? '12:00',
                'snack_time' => \App\Models\AdminSetting::where('key', 'snack_time')->value('value') ?? '15:00',
                'nap_time' => \App\Models\AdminSetting::where('key', 'nap_time')->value('value') ?? '13:00',
            ],
            'classrooms' => \App\Models\Classroom::all(),
            'general' => [
                'system_name' => \App\Models\AdminSetting::where('key', 'system_name')->value('value') ?? 'Childcare Management System',
                'contact_email' => \App\Models\AdminSetting::where('key', 'contact_email')->value('value') ?? '',
                'contact_phone' => \App\Models\AdminSetting::where('key', 'contact_phone')->value('value') ?? '',
                'address' => \App\Models\AdminSetting::where('key', 'address')->value('value') ?? '',
                'max_capacity' => \App\Models\AdminSetting::where('key', 'max_capacity')->value('value') ?? 0,
            ],
        ];

        $available_classes = \App\Models\Child::distinct()->pluck('class')->filter()->values();
        $caregivers = \App\Models\User::where('role', 'caregiver')->get();

        return view('admin.settings', compact('settings', 'available_classes', 'caregivers'));
    }

    /**
     * Feature #13: Update configurations
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'weekly_fee' => 'nullable|numeric|min:0',
            'monthly_fee' => 'nullable|numeric|min:0',
            'sibling_discount' => 'nullable|numeric|min:0|max:100',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'breakfast_time' => 'nullable|date_format:H:i',
            'lunch_time' => 'nullable|date_format:H:i',
            'snack_time' => 'nullable|date_format:H:i',
            'nap_time' => 'nullable|date_format:H:i',
            'system_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'max_capacity' => 'nullable|integer|min:0',
        ]);

        // Save settings to DB
        $settings = $request->only([
            'weekly_fee', 'monthly_fee', 'sibling_discount',
            'opening_time', 'closing_time', 'breakfast_time', 'lunch_time', 'snack_time', 'nap_time',
            'system_name', 'contact_email', 'contact_phone', 'address', 'max_capacity'
        ]);

        foreach ($settings as $key => $value) {
            if (!is_null($value)) {
                \App\Models\AdminSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Feature #13: Manage classrooms
     */
    public function storeClassroom(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'teacher_name' => 'nullable|string|max:255',
        ]);

        \App\Models\Classroom::create($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Classroom added successfully!');
    }

    public function updateClassroom(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'teacher_name' => 'nullable|string|max:255',
        ]);

        $classroom = \App\Models\Classroom::findOrFail($id);
        $classroom->update($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Classroom updated successfully!');
    }

    public function deleteClassroom($id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()->route('admin.settings')
            ->with('success', 'Classroom deleted successfully!');
    }

    /**
     * Feature #11: Communication Logs
     */
    public function communicationLogs()
    {
        $adminId = auth()->id();
        
        // 1. Get IDs of users who have exchanged messages with admin
        $messagedUserIds = \App\Models\Message::where('sender_id', $adminId)
            ->pluck('receiver_id')
            ->merge(\App\Models\Message::where('receiver_id', $adminId)->pluck('sender_id'))
            ->unique();

        $partners = \App\Models\User::whereIn('id', $messagedUserIds)->get();

        // Build conversations array
        $conversations = [];
        foreach ($partners as $partner) {
            // Get latest message
            $latestMessage = \App\Models\Message::where(function($query) use ($adminId, $partner) {
                $query->where('sender_id', $adminId)
                      ->where('receiver_id', $partner->id);
            })->orWhere(function($query) use ($adminId, $partner) {
                $query->where('sender_id', $partner->id)
                      ->where('receiver_id', $adminId);
            })->latest()->first();

            // Count unread messages from this partner
            $unreadMessagesCount = \App\Models\Message::where('sender_id', $partner->id)
                ->where('receiver_id', $adminId)
                ->where('is_read', false)
                ->count();

            $conversations[] = [
                'partner' => $partner,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadMessagesCount,
            ];
        }

        // Sort by latest message
        usort($conversations, function($a, $b) {
            $timeA = $a['latest_message'] ? $a['latest_message']->created_at : null;
            $timeB = $b['latest_message'] ? $b['latest_message']->created_at : null;
            if (!$timeA && !$timeB) return 0;
            if (!$timeA) return 1;
            if (!$timeB) return -1;
            return $timeB <=> $timeA;
        });

        // Fetch users for "New Message" dropdown (for new conversations)
        $parents = \App\Models\User::where('role', 'parent')->where('status', 'active')->orderBy('name')->get();
        $staff = \App\Models\User::where('role', 'caregiver')->where('status', 'active')->orderBy('name')->get();

        return view('admin.communication-logs', compact('conversations', 'parents', 'staff'));
    }

    public function getConversation($userId)
    {
        $adminId = auth()->id();

        // Fetch messages
        $messages = \App\Models\Message::where(function($query) use ($adminId, $userId) {
            $query->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($adminId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $adminId);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark as read
        \App\Models\Message::where('sender_id', $userId)
            ->where('receiver_id', $adminId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'nullable', 
            'receiver_id' => 'nullable', // Allow alias
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'message' => 'Message or attachment is required'], 422);
        }

        $senderId = auth()->id();
        $recipientId = $request->input('recipient_id') ?? $request->input('receiver_id');
        
        if (!$recipientId) {
             return response()->json(['error' => 'Recipient is required'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('attachments', $filename, 'public');
            $attachmentType = $file->getMimeType();
        }

        $messageContent = $validated['message'];
        $message = null;

        if ($recipientId === 'all_parents') {
            $recipients = \App\Models\User::where('role', 'parent')->where('status', 'active')->get();
            foreach ($recipients as $recipient) {
                \App\Models\Message::create([
                    'sender_id' => $senderId,
                    'receiver_id' => $recipient->id,
                    'message' => $messageContent,
                    'is_read' => false,
                    'attachment' => $attachmentPath,
                    'attachment_type' => $attachmentType,
                ]);
            }
            $successMsg = 'Message sent to all parents successfully.';

        } elseif ($recipientId === 'all_staff') {
            $recipients = \App\Models\User::where('role', 'caregiver')->where('status', 'active')->get();
            foreach ($recipients as $recipient) {
                \App\Models\Message::create([
                    'sender_id' => $senderId,
                    'receiver_id' => $recipient->id,
                    'message' => $messageContent,
                    'is_read' => false,
                    'attachment' => $attachmentPath,
                    'attachment_type' => $attachmentType,
                ]);
            }
            $successMsg = 'Message sent to all staff successfully.';

        } else {
            // Single recipient
            $message = \App\Models\Message::create([
                'sender_id' => $senderId,
                'receiver_id' => $recipientId,
                'message' => $messageContent,
                'is_read' => false,
                'attachment' => $attachmentPath,
                'attachment_type' => $attachmentType,
            ]);
            $successMsg = 'Message sent successfully.';
        }

        if ($request->wantsJson()) {
            if ($message) {
                $message->load(['sender', 'receiver']);
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'flash' => $successMsg
            ]);
        }

        return redirect()->route('admin.communication')->with('success', $successMsg);
    }

    /**
     * Feature #14: View specific message
     */
    public function viewMessage($id)
    {
        // Will be implemented when Message model is created
        return view('admin.message-detail');
    }

    /**
     * Feature #14: Edit message
     */
    public function editMessage(Request $request, $id)
    {
        // Will be implemented when Message model is created
        return redirect()->route('admin.communication-logs')
            ->with('success', 'Message updated successfully!');
    }

    /**
     * Feature #14: Delete message
     */
    public function deleteMessage($id)
    {
        // Will be implemented when Message model is created
        return redirect()->route('admin.communication-logs')
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Display the full list of system activities.
     */
    public function activities()
    {
        // Fetch larger dataset for the full view
        $recentUsers = User::latest()->take(20)->get()->map(function ($user) {
            return [
                'type' => 'user',
                'title' => 'New User Registered',
                'description' => $user->name . ' (' . ucfirst($user->role) . ') joined',
                'time' => $user->created_at,
                'icon' => 'fas fa-user-plus',
                'color' => 'user'
            ];
        });

        $recentChildren = \App\Models\Child::latest()->take(20)->get()->map(function ($child) {
            return [
                'type' => 'child',
                'title' => 'Child Registration',
                'description' => $child->first_name . ' ' . $child->last_name . ' registered',
                'time' => $child->created_at,
                'icon' => 'fas fa-child',
                'color' => 'success'
            ];
        });

        $recentApplications = \App\Models\JobApplication::latest()->take(20)->get()->map(function ($app) {
            return [
                'type' => 'application',
                'title' => 'Job Application',
                'description' => $app->full_name . ' applied for ' . $app->position,
                'time' => $app->created_at,
                'icon' => 'fas fa-briefcase',
                'color' => 'orange'
            ];
        });

        // Merge, sort by time desc
        $activities = $recentUsers->merge($recentChildren)
            ->merge($recentApplications)
            ->sortByDesc('time')
            ->values(); // Reset keys for cleaner looping

        return view('admin.activities', compact('activities'));
    }

    /**
     * Display the consolidated pending actions page.
     */
    public function pendingActions()
    {
        $pendingChildren = \App\Models\Child::where('status', 'pending')->with('parent')->latest()->get();
        $pendingApplications = \App\Models\JobApplication::where('status', 'pending')->latest()->get();
        // Placeholder for payments until model exists
        $pendingPayments = []; 

        return view('admin.pending', compact('pendingChildren', 'pendingApplications', 'pendingPayments'));
    }
}
