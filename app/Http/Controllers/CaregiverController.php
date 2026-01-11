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
                    'link' => route('caregiver.daily-reports')
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
            'attendance.*.check_in_time' => [
                'nullable', 
                function ($attribute, $value, $fail) use ($request) {
                    preg_match('/attendance\.(\d+)\.check_in_time/', $attribute, $matches);
                    $childId = $matches[1] ?? null;
                    $status = $request->input("attendance.{$childId}.status");

                    if (in_array($status, ['present', 'late']) && empty($value)) {
                        $fail('Check-in time is required when status is ' . ucfirst($status) . '.');
                    }

                    if ($value && ($value < '08:00' || $value > '18:00')) {
                        $fail('Check-in time must be between 08:00 AM and 06:00 PM.');
                    }
                },
            ],
            'attendance.*.check_out_time' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && ($value < '08:00' || $value > '18:00')) {
                        $fail('Check-out time must be between 08:00 AM and 06:00 PM.');
                    }
                    
                    // Extract child_id from attribute path (e.g., "attendance.123.check_out_time")
                    preg_match('/attendance\.(\d+)\.check_out_time/', $attribute, $matches);
                    if (isset($matches[1])) {
                        $childId = $matches[1];
                        $checkInTime = $request->input("attendance.{$childId}.check_in_time");
                        
                        if ($value && empty($checkInTime)) {
                            $fail('Check-in time is required before setting check-out time.');
                        }
                        
                        // Validate check-out is after check-in
                        if ($value && $checkInTime && $value <= $checkInTime) {
                            $fail('Check-out time must be after check-in time.');
                        }
                    }
                },
            ],
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
        $assignedChildren = $user->assignedChildren()->with(['medications'])->get();
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
            'medication_log' => 'nullable|array',
        ]);

        // Determine status based on action
        $status = $request->input('submit_action') === 'complete' ? 'completed' : 'draft';

        \App\Models\DailyReport::updateOrCreate(
            [
                'child_id' => $request->child_id,
                'caregiver_id' => auth()->id(),
                'report_date' => $request->report_date,
            ],
            [
                'mood' => $request->mood,
                'meals' => $request->meals,
                'nap_duration' => $request->nap_duration,
                'nap_quality' => $request->nap_quality,
                'activities' => $request->activities,
                'notes' => $request->notes,
                'medications_included' => $request->medication_log,
                'status' => $status,
            ]
        );

        $message = $status === 'completed' ? 'Daily report submitted successfully!' : 'Draft saved successfully.';

        return redirect()->back()->with('success', $message);
    }

    public function checkDailyReport(Request $request)
    {
        $report = \App\Models\DailyReport::where('child_id', $request->child_id)
            ->where('report_date', $request->date)
            ->first();

        return response()->json($report);
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
                $query->where('status', 'active')
                      ->where(function($q) {
                          $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', now()->toDateString());
                      });
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
            ->with(['medications' => function ($query) {
                $query->where('status', 'active')
                      ->where(function($q) {
                          $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', now()->toDateString());
                      });
            }, 'vaccinations', 'healthRecords' => function ($query) {
                $query->latest('record_date');
            }, 'checkups' => function ($query) {
                $query->latest('checkup_date');
            }])
            ->findOrFail($id);

        return view('caregiver.child-health-details', compact('child'));
    }

    public function messages()
    {
        // Get all children assigned to this caregiver
        $assignedChildren = auth()->user()->assignedChildren;
        
        // Get all unique parents of these children
        $parents = \App\Models\Child::whereHas('caregivers', function($query) {
            $query->where('users.id', auth()->id());
        })
        ->with('parent')
        ->get()
        ->pluck('parent')
        ->unique('id')
        ->filter(); // Remove null values
        
        // Build conversations array with latest message and unread count
        $conversations = [];
        foreach ($parents as $parent) {
            // Get latest message between caregiver and parent
            $latestMessage = \App\Models\Message::where(function($query) use ($parent) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $parent->id);
            })->orWhere(function($query) use ($parent) {
                $query->where('sender_id', $parent->id)
                      ->where('receiver_id', auth()->id());
            })->latest()->first();
            
            // Count unread messages from this parent
            $unreadMessagesCount = \App\Models\Message::where('sender_id', $parent->id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();
            
            $conversations[] = [
                'parent' => $parent,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadMessagesCount,
            ];
        }
        
        // Sort conversations by latest message time
        usort($conversations, function($a, $b) {
            $timeA = $a['latest_message'] ? $a['latest_message']->created_at : null;
            $timeB = $b['latest_message'] ? $b['latest_message']->created_at : null;
            
            if (!$timeA && !$timeB) return 0;
            if (!$timeA) return 1;
            if (!$timeB) return -1;
            
            return $timeB <=> $timeA;
        });
        
        return view('caregiver.messages', compact('conversations'));
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
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        $unreadCount = auth()->user()->unreadNotifications->count();
        return view('caregiver.notifications', compact('notifications', 'unreadCount'));
    }

    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted.');
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

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'child_id' => 'nullable|exists:children,id',
        ]);

        // Verify the receiver is a parent of a child assigned to this caregiver
        $isValidParent = \App\Models\Child::where('parent_id', $validated['receiver_id'])
            ->whereHas('caregivers', function($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        if (!$isValidParent) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create the message
        $message = \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'child_id' => $validated['child_id'] ?? null,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Load relationships for response
        $message->load('sender', 'receiver');

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function getConversation(Request $request, $parentId)
    {
        // Verify the parent has a child assigned to this caregiver
        $isAssigned = \App\Models\Child::where('parent_id', $parentId)
            ->whereHas('caregivers', function($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        if (!$isAssigned) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Fetch all messages between caregiver and parent
        $messages = \App\Models\Message::where(function($query) use ($parentId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $parentId);
        })->orWhere(function($query) use ($parentId) {
            $query->where('sender_id', $parentId)
                  ->where('receiver_id', auth()->id());
        })
        ->with(['sender', 'receiver', 'child'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark unread messages from parent as read
        \App\Models\Message::where('sender_id', $parentId)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function markAsRead(Request $request, $messageId)
    {
        $message = \App\Models\Message::findOrFail($messageId);
        
        // Ensure the message belongs to the current user
        if ($message->receiver_id == auth()->id()) {
            $message->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 403);
    }

    public function ratings()
    {
        $ratings = \App\Models\Rating::where('caregiver_id', auth()->id())
            ->with('parent')
            ->latest()
            ->get();
        return view('caregiver.ratings', compact('ratings'));
    }
}
