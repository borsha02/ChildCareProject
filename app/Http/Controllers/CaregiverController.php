<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $assignedChildren = $user->assignedChildren;
        $today = now()->format('Y-m-d');

        // Calculate Present Today
        $presentCount = \App\Models\Attendance::whereIn('child_id', $assignedChildren->pluck('id'))
            ->where('date', $today)
            ->where('status', 'Present')
            ->count();

        // Calculate Pending Reports & Generate Tasks
        $tasks = [];
        
        // 1. Attendance Task
        $childrenWithAttendance = \App\Models\Attendance::whereIn('child_id', $assignedChildren->pluck('id'))
            ->where('date', $today)
            ->pluck('child_id')
            ->toArray();
            
        $missingAttendanceCount = $assignedChildren->whereNotIn('id', $childrenWithAttendance)->count();
        if ($missingAttendanceCount > 0) {
            $tasks[] = [
                'title' => 'Mark Attendance',
                'description' => "Pending for $missingAttendanceCount children",
                'due' => '9:00 AM',
                'priority' => 'high',
                'link' => route('caregiver.attendance')
            ];
        }

        // 2. Daily Report Tasks
        $childrenWithReports = \App\Models\DailyReport::where('caregiver_id', $user->id)
            ->where('report_date', $today)
            ->pluck('child_id')
            ->toArray();

        foreach ($assignedChildren as $child) {
            if (!in_array($child->id, $childrenWithReports)) {
                $tasks[] = [
                    'title' => "Daily Report: {$child->first_name}",
                    'description' => "Submit report for {$child->first_name} {$child->last_name}",
                    'due' => '4:00 PM',
                    'priority' => 'medium',
                    'link' => route('caregiver.reports')
                ];
            }
        }
            
        $pendingReportsCount = max(0, $assignedChildren->count() - count($childrenWithReports));

        return view('caregiver.dashboard', compact('assignedChildren', 'presentCount', 'pendingReportsCount', 'tasks'));
    }

    public function assignedChildren()
    {
        $user = auth()->user();
        $assignedChildren = $user->assignedChildren()->with('parent')->get();
        
        $presentCount = \App\Models\Attendance::whereIn('child_id', $assignedChildren->pluck('id'))
            ->where('date', now()->format('Y-m-d'))
            ->where('status', 'Present')
            ->count();

        return view('caregiver.assigned-children', ['children' => $assignedChildren, 'presentCount' => $presentCount]);
    }

    public function attendance(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $assignedChildren = auth()->user()->assignedChildren()
            ->with(['attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])
            ->get();

        return view('caregiver.attendance', compact('assignedChildren', 'date'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.check_in_time' => 'nullable',
            'attendance.*.check_out_time' => 'nullable',
            'attendance.*.notes' => 'nullable|string',
        ]);

        $date = $request->input('date');

        foreach ($request->attendance as $childId => $data) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'child_id' => $childId,
                    'date' => $date,
                    'caregiver_id' => auth()->id(),
                ],
                [
                    'status' => $data['status'],
                    'check_in_time' => $data['check_in_time'],
                    'check_out_time' => $data['check_out_time'],
                    'notes' => $data['notes'],
                ]
            );
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!'
            ]);
        }

        return redirect()->route('caregiver.attendance', ['date' => $date])
            ->with('success', 'Attendance marked successfully!');
    }

    public function dailyReports()
    {
        $user = auth()->user();
        $assignedChildren = $user->assignedChildren;
        $today = now()->format('Y-m-d');

        // Recent reports list (limited)
        $recentReports = \App\Models\DailyReport::where('caregiver_id', $user->id)
            ->with('child')
            ->latest('report_date')
            ->take(10)
            ->get();

        // Stats Calculations (Global)
        $reportsTodayCount = \App\Models\DailyReport::where('caregiver_id', $user->id)
            ->where('report_date', $today)
            ->distinct('child_id')
            ->count('child_id');

        $pendingReportsCount = max(0, $assignedChildren->count() - $reportsTodayCount);
        
        $completedWeekCount = \App\Models\DailyReport::where('caregiver_id', $user->id)
            ->where('report_date', '>=', now()->startOfWeek())
            ->where('report_date', '<=', now()->endOfWeek())
            ->count();

        $completionRate = $assignedChildren->count() > 0 
            ? round(($reportsTodayCount / $assignedChildren->count()) * 100) 
            : 0;

        return view('caregiver.daily-reports', compact(
            'assignedChildren', 
            'recentReports',
            'reportsTodayCount',
            'pendingReportsCount',
            'completedWeekCount',
            'completionRate'
        ));
    }

    public function storeDailyReport(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:children,id',
            'report_date' => 'required|date',
            'mood' => 'nullable|string',
            'meals' => 'nullable|array',
            'nap_duration' => 'nullable|integer',
            'nap_quality' => 'nullable|string',
            'activities' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        \App\Models\DailyReport::create([
            'child_id' => $request->child_id,
            'caregiver_id' => auth()->id(),
            'report_date' => $request->report_date,
            'mood' => $request->mood,
            'meals' => $request->meals,
            'nap_duration' => $request->nap_duration,
            'nap_quality' => $request->nap_quality,
            'activities' => $request->activities,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Daily report submitted successfully!');
    }

    public function healthRecords()
    {
        // Eager load necessary relationships
        // For stats we need all active medications to count them
        // For health records list, we usually show latest, but for the stat "Health Records" (implied availability)
        // checking if any exist is enough.
        $assignedChildren = auth()->user()->assignedChildren()
            ->with(['healthRecords' => function ($query) {
                $query->latest('record_date'); // Load all to check existence or specific count? Plan said "isNotEmpty".
                                               // Limit 1 is enough for "isNotEmpty" but if we want to show history later...
                                               // Let's keep it optimized if possible.
                                               // Actually, if we remove limit, we can show sparklines or more history?
                                               // For now, let's stick to efficient loading.
                                               // But wait, the view card says "Health Records". 
                                               // Implementation plan: "Children having at least one healthRecord".
                                               // So limit(1) is fine.
                $query->limit(1);
            }, 'medications' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();

        // Stats Calculations
        $allergiesCount = $assignedChildren->filter(function ($child) {
            return !empty($child->allergies);
        })->count();

        $medicationsCount = $assignedChildren->sum(function ($child) {
            return $child->medications->count();
        });

        $recordsCount = $assignedChildren->sum(function ($child) {
            return $child->healthRecords->count();
        });

        return view('caregiver.health-records', compact('assignedChildren', 'allergiesCount', 'medicationsCount', 'recordsCount'));
    }

    public function showChildHealth($id)
    {
        $child = auth()->user()->assignedChildren()
            ->with(['medications', 'vaccinations', 'healthRecords' => function ($query) {
                $query->latest('record_date');
            }, 'checkups' => function ($query) {
                $query->latest('checkup_date');
            }])
            ->findOrFail($id);

        return view('caregiver.child-health-details', compact('child'));
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
