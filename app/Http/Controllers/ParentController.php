<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function dashboard()
    {
        $children = \App\Models\Child::where('parent_id', auth()->id())->get();
        $childrenCount = $children->count();
        $healthRecordsCount = \App\Models\HealthRecord::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })->count();
        return view('parent.dashboard', compact('children', 'childrenCount', 'healthRecordsCount'));
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
            'package' => 'required|in:monthly,weekly',
            'duration' => 'nullable|integer|between:1,3|required_if:package,weekly',
            'enrollment_date' => 'required|date',
        ]);

        $child = new \App\Models\Child($validated);
        $child->status = 'pending'; // Explicitly set to pending for approval workflow
        $child->enrollment_date = $validated['enrollment_date'];
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
            'package' => 'required|in:monthly,weekly',
            'duration' => 'nullable|integer|between:1,3|required_if:package,weekly',
            'enrollment_date' => 'required|date',
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


    public function reports(Request $request)
    {
        $unreadCount = auth()->user()->unreadNotifications->count();
        // Fetch all children that are NOT pending (assuming 'pending' is the status for unapproved)
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->where('status', '!=', 'pending') 
            ->get();
            
        // Determine which children's data to show
        if ($request->has('child_id') && $request->child_id != 'all') {
            // Verify the requested child belongs to the parent
            $selectedChild = $children->where('id', $request->child_id)->first();
            $childIds = $selectedChild ? collect([$selectedChild->id]) : $children->pluck('id');
        } else {
            $childIds = $children->pluck('id');
        }

        // Determine time period for filtering
        $period = $request->get('period', 'month'); // Default to 'month'
        
        // Build date query based on period
        $reportsQuery = \App\Models\DailyReport::whereIn('child_id', $childIds);
        $statsQuery = \App\Models\DailyReport::whereIn('child_id', $childIds);
        $attendanceQuery = \App\Models\Attendance::whereIn('child_id', $childIds)->where('status', 'present');
        
        if ($period === 'week') {
            $reportsQuery->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()]);
            $statsQuery->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()]);
            $attendanceQuery->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $reportsQuery->whereMonth('report_date', now()->month)->whereYear('report_date', now()->year);
            $statsQuery->whereMonth('report_date', now()->month)->whereYear('report_date', now()->year);
            $attendanceQuery->whereMonth('date', now()->month)->whereYear('date', now()->year);
        } elseif ($period === 'year') {
            $reportsQuery->whereYear('report_date', now()->year);
            $statsQuery->whereYear('report_date', now()->year);
            $attendanceQuery->whereYear('date', now()->year);
        }
        // 'all' means no date filter

        // Check if viewing all reports or just recent (limit to 10)
        $viewAll = $request->get('view_all', false);
        
        // Recent Reports Table
        $recentReportsQuery = $reportsQuery->with('child')->latest('report_date');
        $recentReports = $viewAll ? $recentReportsQuery->get() : $recentReportsQuery->take(10)->get();

        // --- Calculate Stats ---
        $filteredReports = $statsQuery->get();

        // 1. Total Daily Reports
        $totalReports = $filteredReports->count();

        // 2. Average Nap Duration
        $avgNapDuration = $filteredReports->avg('nap_duration') ?? 0; // in minutes

        // 3. Activity Stats & Summary
        $activityCounts = [];
        $totalActivities = 0;

        foreach ($filteredReports as $report) {
            $activities = $report->activities ?? [];
            if (is_string($activities)) {
                 $activities = json_decode($activities, true) ?? [];
            }
            
            if (is_array($activities)) {
                $totalActivities += count($activities);
                foreach ($activities as $activity) {
                    $name = is_array($activity) ? ($activity['name'] ?? 'Unknown') : $activity;
                    if (!isset($activityCounts[$name])) {
                        $activityCounts[$name] = 0;
                    }
                    $activityCounts[$name]++;
                }
            }
        }
        
        // Sort activities by popularity
        arsort($activityCounts);
        $topActivities = array_slice($activityCounts, 0, 5); // Take top 5

        // 4. Attendance Count
        $attendanceCount = $attendanceQuery->count();
            
        // Latest Teacher Note (from most recent report)
        $latestReport = $recentReports->first();
        $latestTeacherNote = $latestReport ? $latestReport->notes : 'No recent notes available.';

        return view('parent.reports', compact(
            'unreadCount', 
            'children', 
            'recentReports',
            'totalReports',
            'avgNapDuration',
            'totalActivities',
            'attendanceCount',
            'topActivities',
            'latestTeacherNote',
            'period'
        ));
    }

    public function attendance(Request $request)
    {
        $unreadCount = auth()->user()->unreadNotifications->count();
        
        // Fetch active children (for the filter buttons)
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->where('status', '!=', 'pending')
            ->get();

        // Determine which children's data to show
        if ($request->has('child_id') && $request->child_id != 'all') {
            $childIds = $children->where('id', $request->child_id)->pluck('id');
            // If invalid child_id (not belonging to parent), fall back to all
            if ($childIds->isEmpty()) {
                $childIds = $children->pluck('id');
            }
        } else {
            $childIds = $children->pluck('id');
        }
        
        // Get current month/year or from request
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Fetch attendance records for current month
        $attendanceRecords = \App\Models\Attendance::whereIn('child_id', $childIds)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
        
        // Calculate stats
        $totalDays = $attendanceRecords->count();
        $daysPresent = $attendanceRecords->where('status', 'present')->count();
        $daysAbsent = $attendanceRecords->where('status', 'absent')->count();
        $timesLate = $attendanceRecords->where('status', 'late')->count();
        $attendanceRate = $totalDays > 0 ? round(($daysPresent / $totalDays) * 100) : 0;
        
        // Build calendar data (group by date)
        $calendarData = [];
        foreach ($attendanceRecords as $record) {
            $day = \Carbon\Carbon::parse($record->date)->day;
            if (!isset($calendarData[$day])) {
                $calendarData[$day] = [];
            }
            $calendarData[$day][] = $record;
        }
        
        // Fetch recent attendance history (last 20 records)
        $recentAttendance = \App\Models\Attendance::whereIn('child_id', $childIds)
            ->with('child')
            ->latest('date')
            ->take(20)
            ->get();
        
        // Get month info for calendar generation
        $firstDayOfMonth = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Sunday
        
        return view('parent.attendance', compact(
            'unreadCount',
            'children',
            'attendanceRate',
            'daysPresent',
            'daysAbsent',
            'timesLate',
            'totalDays',
            'calendarData',
            'recentAttendance',
            'month',
            'year',
            'daysInMonth',
            'startDayOfWeek'
        ));
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
        $unreadCount = auth()->user()->unreadNotifications->count();
        
        // Get all children belonging to the authenticated parent
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->where('status', '!=', 'pending')
            ->get();
        
        // Get all unique caregivers assigned to these children
        $caregivers = \App\Models\Child::where('parent_id', auth()->id())
            ->with('caregivers')
            ->get()
            ->pluck('caregivers')
            ->flatten()
            ->unique('id');
        
        // Build conversations array with latest message and unread count
        $conversations = [];
        foreach ($caregivers as $caregiver) {
            // Get latest message between parent and caregiver
            $latestMessage = \App\Models\Message::where(function($query) use ($caregiver) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $caregiver->id);
            })->orWhere(function($query) use ($caregiver) {
                $query->where('sender_id', $caregiver->id)
                      ->where('receiver_id', auth()->id());
            })->latest()->first();
            
            // Count unread messages from this caregiver
            $unreadMessagesCount = \App\Models\Message::where('sender_id', $caregiver->id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();
            
            $conversations[] = [
                'caregiver' => $caregiver,
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
        
        return view('parent.messages', compact('unreadCount', 'conversations'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        $unreadCount = auth()->user()->unreadNotifications->count();
        return view('parent.notifications', compact('notifications', 'unreadCount'));
    }

    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return response()->json(['success' => true]);
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
        $unreadCount = auth()->user()->unreadNotifications->count();
        
        // Fetch caregivers assigned to the parent's children
        // We get all children of the parent, then pluck their caregivers, collapse into one collection, and make unique
        $caregivers = \App\Models\Child::where('parent_id', auth()->id())
            ->with('caregivers')
            ->get()
            ->pluck('caregivers')
            ->flatten()
            ->unique('id');

        return view('parent.caregivers', compact('unreadCount', 'caregivers'));
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

    public function deleteHealthRecord($id)
    {
        $healthRecord = \App\Models\HealthRecord::findOrFail($id);
        
        // Verify ownership via child relationship
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($healthRecord->child_id);

        $healthRecord->delete();

        return redirect()->route('parent.health')->with('success', 'Health record deleted successfully!');
    }

    public function updateMedication(Request $request, $id)
    {
        $medication = \App\Models\Medication::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })->findOrFail($id);

        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,discontinued',
            'notes' => 'nullable|string',
        ]);

        $medication->update($validated);

        return redirect()->route('parent.medications')->with('success', 'Medication record updated successfully!');
    }

    public function deleteMedication($id)
    {
        $medication = \App\Models\Medication::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })->findOrFail($id);

        $medication->delete();

        return redirect()->route('parent.medications')->with('success', 'Medication record deleted successfully!');
    }

    public function updateAllergies(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'allergies' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        $child = \App\Models\Child::where('id', $validated['child_id'])
            ->where('parent_id', auth()->id())
            ->firstOrFail();

        $child->update([
            'allergies' => $validated['allergies'],
            'medical_notes' => $validated['medical_notes'],
            'emergency_contact' => $validated['emergency_contact'],
        ]);

        return redirect()->route('parent.health')->with('success', 'Allergies and medical conditions updated successfully!');
    }

    public function storeCheckup(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'checkup_type' => 'required|string|max:255',
            'checkup_date' => 'required|date|before_or_equal:today',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_specialty' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'bmi' => 'nullable|numeric|min:0|max:99.99',
            'notes' => 'nullable|string',
        ]);

        // Verify child belongs to authenticated parent
        \App\Models\Child::where('parent_id', auth()->id())->findOrFail($validated['child_id']);

        \App\Models\Checkup::create($validated);

        return redirect()->route('parent.health')->with('success', 'Checkup record added successfully!');
    }

    public function updateCheckup(Request $request, $id)
    {
        $checkup = \App\Models\Checkup::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })->findOrFail($id);

        $validated = $request->validate([
            'checkup_type' => 'required|string|max:255',
            'checkup_date' => 'required|date|before_or_equal:today',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_specialty' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'bmi' => 'nullable|numeric|min:0|max:99.99',
            'notes' => 'nullable|string',
        ]);

        $checkup->update($validated);

        return redirect()->route('parent.health')->with('success', 'Checkup record updated successfully!');
    }

    public function deleteCheckup($id)
    {
        $checkup = \App\Models\Checkup::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })->findOrFail($id);

        $checkup->delete();

        return redirect()->route('parent.health')->with('success', 'Checkup record deleted successfully!');
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'child_id' => 'nullable|exists:children,id',
        ]);

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

    public function getConversation(Request $request, $caregiverId)
    {
        // Verify the caregiver is assigned to one of the parent's children
        $isAssigned = \App\Models\Child::where('parent_id', auth()->id())
            ->whereHas('caregivers', function($query) use ($caregiverId) {
                $query->where('users.id', $caregiverId);
            })
            ->exists();

        if (!$isAssigned) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Fetch all messages between parent and caregiver
        $messages = \App\Models\Message::where(function($query) use ($caregiverId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $caregiverId);
        })->orWhere(function($query) use ($caregiverId) {
            $query->where('sender_id', $caregiverId)
                  ->where('receiver_id', auth()->id());
        })
        ->with(['sender', 'receiver', 'child'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark unread messages from caregiver as read
        \App\Models\Message::where('sender_id', $caregiverId)
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
        $message = \App\Models\Message::where('receiver_id', auth()->id())
            ->findOrFail($messageId);

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

}
