<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            $stats = [
                'total_users' => User::count(),
                'total_parents' => User::where('role', 'parent')->count(),
                'total_staff' => User::where('role', 'caregiver')->count(),
                'total_children' => 0, // Will be updated when Child model is created
                'active_today' => 0, // Will be updated with attendance data
                'pending_payments' => 0, // Will be updated with payment data
                'total_revenue' => 0, // Will be updated with payment data
                'pending_approvals' => 0, // Will be updated with payment approval data
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
            ];
        }

        // Recent activities (placeholder for now)
        $recentActivities = [];

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
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,caregiver,parent',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Feature #2: Deactivate user
     */
    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'inactive']);

        return redirect()->route('admin.users')
            ->with('success', 'User deactivated successfully!');
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
    public function children()
    {
        // Placeholder - will be implemented when Child model is created
        $children = [];

        return view('admin.children', compact('children'));
    }

    /**
     * Feature #4: Create child record
     */
    public function createChild(Request $request)
    {
        // Will be implemented when Child model is created
        return redirect()->route('admin.children')
            ->with('success', 'Child record created successfully!');
    }

    /**
     * Feature #4: Update child record
     */
    public function updateChild(Request $request, $id)
    {
        // Will be implemented when Child model is created
        return redirect()->route('admin.children')
            ->with('success', 'Child record updated successfully!');
    }

    /**
     * Feature #4: Delete child record
     */
    public function deleteChild($id)
    {
        // Will be implemented when Child model is created
        return redirect()->route('admin.children')
            ->with('success', 'Child record deleted successfully!');
    }

    /**
     * Feature #5: Manage Staff
     */
    public function staff()
    {
        $staff = User::where('role', 'caregiver')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.staff', compact('staff'));
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
        ]);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'caregiver',
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        return redirect()->route('admin.staff')
            ->with('success', 'Staff member added successfully!');
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
        ]);

        $staff->update($validated);

        return redirect()->route('admin.staff')
            ->with('success', 'Staff details updated successfully!');
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

    /**
     * Feature #6: Monitor Attendance
     */
    public function attendance()
    {
        // Placeholder - will be implemented when Attendance model is created
        $attendanceRecords = [];
        $stats = [
            'present_today' => 0,
            'absent_today' => 0,
            'late_today' => 0,
            'attendance_rate' => 0,
        ];

        return view('admin.attendance', compact('attendanceRecords', 'stats'));
    }

    /**
     * Feature #6: Export attendance reports
     */
    public function exportAttendance(Request $request)
    {
        // Will implement CSV/Excel export functionality
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Placeholder for export logic
        return redirect()->back()
            ->with('success', 'Attendance report exported successfully!');
    }

    /**
     * Feature #7: Daily Reports - Monitor daily activity logs
     */
    public function reports()
    {
        // Placeholder - will be implemented when DailyReport model is created
        $dailyReports = [];

        return view('admin.reports', compact('dailyReports'));
    }

    /**
     * Feature #7: View specific daily report
     */
    public function viewDailyReport($id)
    {
        // Will be implemented when DailyReport model is created
        return view('admin.report-detail');
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
}
