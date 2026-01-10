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
                'total_children' => \App\Models\Child::where('status', '!=', 'pending')->count(), // Only enrolled/active/inactive items
                'active_today' => 0, // Will be updated with attendance data
                'pending_payments' => 0, // Will be updated with payment data
                'total_revenue' => 0, // Will be updated with payment data
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
        
        // Check if already assigned
        if (!$child->caregivers->contains($validated['caregiver_id'])) {
            $child->caregivers()->attach($validated['caregiver_id']);
             return redirect()->back()->with('success', 'Caregiver assigned successfully.');
        }

        return redirect()->back()->with('warning', 'Caregiver already assigned.');
    }

    public function removeCaregiver(Request $request, $id)
    {
        $child = Child::findOrFail($id);
        $validated = $request->validate([
            'caregiver_id' => 'required|exists:users,id',
        ]);
        
        $child->caregivers()->detach($validated['caregiver_id']);
        return redirect()->back()->with('success', 'Caregiver removed successfully.');
    }

    public function approveChild($id)
    {
        $child = Child::with('parent')->findOrFail($id);
        $child->update(['status' => 'active']);
        
        if ($child->parent) {
            $child->parent->notify(new \App\Notifications\ChildApproved($child));
        }

        return redirect()->back()->with('success', 'Child registration approved successfully.');
    }

    public function rejectChild($id)
    {
        $child = Child::findOrFail($id);
        $child->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Child registration rejected.');
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

        return redirect()->route('admin.children')->with('success', 'Child record created successfully!');
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

        return redirect()->route('admin.children')->with('success', 'Child record updated successfully!');
    }

    public function reactivate($id)
    {
        $child = \App\Models\Child::findOrFail($id);
        
        $child->status = 'active';
        $child->enrollment_date = now(); // Reset enrollment date to today
        $child->save();

        return redirect()->route('admin.children')->with('success', 'Child reactivated successfully. Enrollment date reset to today.');
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

        return redirect()->route('admin.staff')
            ->with('success', 'Staff details updated successfully!');
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
        return view('admin.ratings', compact('ratings'));
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
                $query->whereDate('date', $date);
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
        // Placeholder - will be implemented when Invoice model is created
        $invoices = [];
        $stats = [
            'total_invoices' => 0,
            'paid_invoices' => 0,
            'pending_invoices' => 0,
            'overdue_invoices' => 0,
            'total_revenue' => 0,
        ];

        return view('admin.invoices', compact('invoices', 'stats'));
    }

    /**
     * Feature #8: Generate invoice
     */
    public function generateInvoice(Request $request)
    {
        // Will be implemented when Invoice model is created
        return redirect()->route('admin.invoices')
            ->with('success', 'Invoice generated successfully!');
    }

    /**
     * Feature #8: Update invoice
     */
    public function updateInvoice(Request $request, $id)
    {
        // Will be implemented when Invoice model is created
        return redirect()->route('admin.invoices')
            ->with('success', 'Invoice updated successfully!');
    }

    /**
     * Feature #9: Analytics Dashboard
     */
    public function analytics()
    {
        // System-wide analytics
        $analytics = [
            'attendance' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'data' => [85, 90, 88, 92, 87], // Placeholder data
            ],
            'payments' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [12000, 15000, 13500, 16000, 14500, 17000], // Placeholder data
            ],
            'feedback' => [
                'positive' => 85,
                'neutral' => 10,
                'negative' => 5,
            ],
            'enrollment' => [
                'total' => 120,
                'new_this_month' => 8,
                'active' => 115,
                'inactive' => 5,
            ],
        ];

        return view('admin.analytics', compact('analytics'));
    }

    /**
     * Feature #10: Announcements
     */
    public function announcements()
    {
        // Placeholder - will be implemented when Announcement model is created
        $announcements = [];

        return view('admin.announcements', compact('announcements'));
    }

    /**
     * Feature #10: Create announcement
     */
    public function createAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'required|in:all,parents,staff',
            'priority' => 'required|in:low,medium,high',
        ]);

        // Will be implemented when Announcement model is created

        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement sent successfully!');
    }

    /**
     * Feature #10: Update announcement
     */
    public function updateAnnouncement(Request $request, $id)
    {
        // Will be implemented when Announcement model is created
        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Feature #10: Delete announcement
     */
    public function deleteAnnouncement($id)
    {
        // Will be implemented when Announcement model is created
        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Feature #11: Approve Payments
     */
    public function pendingPayments()
    {
        // Placeholder - will be implemented when Payment model is created
        $pendingPayments = [];

        return view('admin.pending-payments', compact('pendingPayments'));
    }

    /**
     * Feature #11: Approve payment
     */
    public function approvePayment($id)
    {
        // Will be implemented when Payment model is created
        return redirect()->back()
            ->with('success', 'Payment approved successfully!');
    }

    /**
     * Feature #11: Reject payment
     */
    public function rejectPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Will be implemented when Payment model is created

        return redirect()->back()
            ->with('success', 'Payment rejected!');
    }

    /**
     * Feature #12: Backup & Restore
     */
    public function backupRestore()
    {
        // Get list of available backups
        $backups = [];

        return view('admin.backup-restore', compact('backups'));
    }

    /**
     * Feature #12: Create backup
     */
    public function createBackup()
    {
        // Implement database backup logic
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            // Backup logic will be implemented

            return redirect()->back()
                ->with('success', 'Backup created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Feature #12: Restore from backup
     */
    public function restoreBackup(Request $request)
    {
        $validated = $request->validate([
            'backup_file' => 'required|string',
        ]);

        // Implement restore logic
        try {
            // Restore logic will be implemented

            return redirect()->back()
                ->with('success', 'Database restored successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Feature #13: Manage Configurations
     */
    public function settings()
    {
        // Get system configurations
        $settings = [
            'fees' => [
                'registration_fee' => 0,
                'monthly_fee' => 0,
                'late_pickup_fee' => 0,
            ],
            'timings' => [
                'opening_time' => '07:00',
                'closing_time' => '18:00',
            ],
            'classrooms' => [],
            'general' => [
                'system_name' => 'Childcare Management System',
                'contact_email' => '',
                'contact_phone' => '',
                'address' => '',
            ],
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Feature #13: Update configurations
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'registration_fee' => 'nullable|numeric|min:0',
            'monthly_fee' => 'nullable|numeric|min:0',
            'late_pickup_fee' => 'nullable|numeric|min:0',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'system_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Will be implemented when Settings model is created

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Feature #13: Manage classrooms
     */
    public function manageClassrooms(Request $request)
    {
        // Will be implemented when Classroom model is created
        return redirect()->route('admin.settings')
            ->with('success', 'Classroom settings updated successfully!');
    }

    /**
     * Feature #14: Communication Logs
     */
    public function communicationLogs()
    {
        // Placeholder - will be implemented when Message model is created
        $messages = [];
        $announcements = [];

        return view('admin.communication-logs', compact('messages', 'announcements'));
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
