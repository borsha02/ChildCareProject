<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

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
        // 1. Get parents of assigned children
        $assignedParents = \App\Models\Child::whereHas('caregivers', function($query) {
            $query->where('users.id', auth()->id());
        })
        ->with('parent')
        ->get()
        ->pluck('parent')
        ->unique('id')
        ->filter();

        // 2. Get any other users we have messaged with (e.g. Admins)
        $caregiverId = auth()->id();
        $messagedUserIds = \App\Models\Message::where('sender_id', $caregiverId)
            ->pluck('receiver_id')
            ->merge(\App\Models\Message::where('receiver_id', $caregiverId)->pluck('sender_id'))
            ->unique();

        $messagedUsers = \App\Models\User::whereIn('id', $messagedUserIds)->get();

        // 3. Merge and unique
        $allPartners = $assignedParents->merge($messagedUsers)->unique('id');
        
        // Build conversations array with latest message and unread count
        $conversations = [];
        foreach ($allPartners as $partner) {
            // Get latest message between caregiver and partner
            $latestMessage = \App\Models\Message::where(function($query) use ($partner, $caregiverId) {
                $query->where('sender_id', $caregiverId)
                      ->where('receiver_id', $partner->id);
            })->orWhere(function($query) use ($partner, $caregiverId) {
                $query->where('sender_id', $partner->id)
                      ->where('receiver_id', $caregiverId);
            })->latest()->first();
            
            // Count unread messages from this partner
            $unreadMessagesCount = \App\Models\Message::where('sender_id', $partner->id)
                ->where('receiver_id', $caregiverId)
                ->where('is_read', false)
                ->count();
            
            $conversations[] = [
                'partner' => $partner, // Generic 'partner' key, view needs update if it expects 'parent'
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

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'child_id' => 'nullable|exists:children,id',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'message' => 'Message or attachment is required'], 422);
        }

        $receiverId = $validated['receiver_id'];

        // Verify authorization:
        // 1. Is receiver an assigned parent?
        $isAssignedParent = \App\Models\Child::where('parent_id', $receiverId)
            ->whereHas('caregivers', function($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        // 2. Is receiver an admin?
        $receiver = \App\Models\User::find($receiverId);
        $isAdmin = $receiver && $receiver->role === 'admin';

        // 3. Is there existing history?
        $hasHistory = \App\Models\Message::where(function($q) use ($receiverId) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $receiverId);
        })->orWhere(function($q) use ($receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', auth()->id());
        })->exists();

        if (!$isAssignedParent && !$isAdmin && !$hasHistory) {
            return response()->json(['error' => 'Unauthorized'], 403);
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

        // Create the message
        $message = \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'child_id' => $validated['child_id'] ?? null,
            'message' => $validated['message'],
            'is_read' => false,
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // Load relationships for response
        $message->load('sender', 'receiver');

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function getConversation(Request $request, $partnerId)
    {
        // 1. Is Assigned Parent?
        $isAssigned = \App\Models\Child::where('parent_id', $partnerId)
            ->whereHas('caregivers', function($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        // 2. Is Admin?
        $partner = \App\Models\User::find($partnerId);
        $isAdmin = $partner && $partner->role === 'admin';

        // 3. Has History?
        $hasHistory = \App\Models\Message::where(function($query) use ($partnerId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $partnerId);
        })->orWhere(function($query) use ($partnerId) {
            $query->where('sender_id', $partnerId)
                  ->where('receiver_id', auth()->id());
        })->exists();

        if (!$isAssigned && !$isAdmin && !$hasHistory) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Fetch all messages between caregiver and partner
        $messages = \App\Models\Message::where(function($query) use ($partnerId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $partnerId);
        })->orWhere(function($query) use ($partnerId) {
            $query->where('sender_id', $partnerId)
                  ->where('receiver_id', auth()->id());
        })
        ->with(['sender', 'receiver', 'child'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark unread messages from partner as read
        \App\Models\Message::where('sender_id', $partnerId)
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
    public function events()
    {
        $events = \App\Models\Event::with(['registrations.user', 'registrations.child'])
            ->withCount('registrations')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('caregiver.events', compact('events'));
    }
    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        $unreadCount = auth()->user()->unreadNotifications->count();
        return view('caregiver.notifications', compact('notifications', 'unreadCount'));
    }

    public function markAllNotificationsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read.');
    }

    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
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
        $request->validate([
            'leave_type' => 'required|string',
            'duration_type' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    $start = \Carbon\Carbon::parse($request->start_date)->startOfDay();
                    $end = \Carbon\Carbon::parse($value)->startOfDay();
                    $days = $start->diffInDays($end) + 1;

                    if ($request->leave_type === 'Emergency' && $days > 1) {
                        $fail('Emergency leave cannot be more than 1 day.');
                    }
                    if ($request->leave_type === 'Personal' && $days > 3) {
                         $fail('Personal leave cannot be more than 3 days.');
                    }
                },
            ],
            'reason' => 'required|string',
        ]);

        \App\Models\LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type' => $request->leave_type,
            'duration_type' => $request->duration_type ?? 'Full Day',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }
    public function destroyLeaveRequest($id)
    {
        $request = \App\Models\LeaveRequest::where('user_id', auth()->id())->findOrFail($id);

        if ($request->status === 'Pending') {
            return back()->with('error', 'Pending requests cannot be deleted.');
        }

        $request->delete();

        return back()->with('success', 'Leave history deleted successfully.');
    }

    public function settings()
    {
        return view('caregiver.settings');
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        auth()->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function schedule(Request $request)
    {
        // 1. Determine Selected Date
        $selectedDate = $request->has('date') 
            ? \Carbon\Carbon::parse($request->date) 
            : now();
        
        $prevDate = $selectedDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $selectedDate->copy()->addDay()->format('Y-m-d');

        // 2. Fetch Timings (Routine)
        $timings = [
            'opening_time' => \App\Models\AdminSetting::where('key', 'opening_time')->value('value') ?? '07:00',
            'closing_time' => \App\Models\AdminSetting::where('key', 'closing_time')->value('value') ?? '18:00',
            'breakfast_time' => \App\Models\AdminSetting::where('key', 'breakfast_time')->value('value') ?? '08:00',
            'lunch_time' => \App\Models\AdminSetting::where('key', 'lunch_time')->value('value') ?? '12:00',
            'snack_time' => \App\Models\AdminSetting::where('key', 'snack_time')->value('value') ?? '15:00',
            'nap_time' => \App\Models\AdminSetting::where('key', 'nap_time')->value('value') ?? '13:00',
        ];

        // 3. Fetch Events (Database) for selected date only
        $events = \App\Models\Event::whereDate('start_time', $selectedDate)
            ->orderBy('start_time')
            ->get();

        // 4. Build Day Schedule Structure
        $dateKey = $selectedDate->format('Y-m-d');
        $dayName = $selectedDate->format('l');
        $isWeekend = in_array($dayName, ['Friday', 'Saturday']);
        
        // Check for Holiday Event
        $holidayEvent = $events->where('category', 'holiday')->first();

        $daySchedule = [
            'date' => $selectedDate->copy(),
            'is_today' => $selectedDate->isToday(),
            'is_holiday' => $holidayEvent ? true : false,
            'holiday_name' => $holidayEvent ? $holidayEvent->title : null,
            'is_weekend' => $isWeekend,
            'items' => []
        ];

        if (!$daySchedule['is_holiday'] && !$isWeekend) {
            // Add Routine Items
            $daySchedule['items'][] = [
                'time' => $timings['opening_time'],
                'title' => 'Facility Opens',
                'description' => 'Morning Arrival & Check-in',
                'type' => 'routine',
                'icon' => 'fas fa-door-open'
            ];
            $daySchedule['items'][] = [
                'time' => $timings['breakfast_time'],
                'title' => 'Breakfast',
                'description' => 'Healthy breakfast served',
                'type' => 'routine',
                'icon' => 'fas fa-utensils'
            ];
            $daySchedule['items'][] = [
                'time' => $timings['lunch_time'],
                'title' => 'Lunch Time',
                'description' => 'Nutritious lunch served',
                'type' => 'routine',
                'icon' => 'fas fa-hamburger'
            ];
            $daySchedule['items'][] = [
                'time' => $timings['nap_time'],
                'title' => 'Nap Time',
                'description' => 'Rest period for children',
                'type' => 'routine',
                'icon' => 'fas fa-bed'
            ];
            $daySchedule['items'][] = [
                'time' => $timings['snack_time'],
                'title' => 'Afternoon Snack',
                'description' => 'Light snack served',
                'type' => 'routine',
                'icon' => 'fas fa-cookie'
            ];
            $daySchedule['items'][] = [
                'time' => $timings['closing_time'],
                'title' => 'Facility Closes',
                'description' => 'Pickup & Departure',
                'type' => 'routine',
                'icon' => 'fas fa-door-closed'
            ];
        }

        // Add Database Events for this day
        $daysEvents = $events->where('category', '!=', 'holiday');

        foreach ($daysEvents as $event) {
            $daySchedule['items'][] = [
                'time' => $event->start_time->format('H:i'),
                'title' => $event->title,
                'description' => $event->description ?? $event->location,
                'type' => 'event',
                'icon' => 'fas fa-calendar-check'
            ];
        }

        // Sort items by time
        usort($daySchedule['items'], function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        // Fetch Assigned Classrooms
        $classrooms = \App\Models\Classroom::where('teacher_name', auth()->user()->name)->get();

        return view('caregiver.schedule', compact('daySchedule', 'timings', 'classrooms', 'selectedDate', 'prevDate', 'nextDate'));
    }
}
