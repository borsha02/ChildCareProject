<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class ParentController extends Controller
{
    public function dashboard()
    {
        $children = \App\Models\Child::where('parent_id', auth()->id())->get();
        // Card 1: Children Count (Already fetched)
        $childrenCount = $children->count();

        // Card 2: Attendance Rate
        $childIds = $children->pluck('id');
        $attendanceRecords = \App\Models\Attendance::whereIn('child_id', $childIds)->get();
        $totalAttendance = $attendanceRecords->count();
        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;

        // Card 3: Pending Payment (Placeholder)
        $pendingPayment = 0;

        // Card 4: Upcoming Events (Placeholder)
        $upcomingEventsCount = 0;

        // Card 5: Assigned Caregivers
        $caregiversCount = \App\Models\Child::where('parent_id', auth()->id())
            ->where('status', '!=', 'pending') // Only count caregivers for active children
            ->with('caregivers')
            ->get()
            ->pluck('caregivers')
            ->flatten()
            ->unique('id')
            ->count();

        // Card 6: Health Records Count
        $healthRecordsCount = \App\Models\HealthRecord::whereHas('child', function($query) {
            $query->where('parent_id', auth()->id());
        })->count();

        // Today's Activities
        $todaysReports = \App\Models\DailyReport::whereIn('child_id', $childIds)
            ->whereDate('report_date', now())
            ->with('child')
            ->get();

        $todaysActivities = collect();

        foreach ($todaysReports as $report) {
            $childName = $report->child->first_name;

            // Meals
            if (isset($report->meals) && is_array($report->meals)) {
                if (($report->meals['breakfast'] ?? 'None') !== 'None') {
                    $todaysActivities->push([
                        'type' => 'meal',
                        'icon' => 'fas fa-utensils',
                        'title' => 'Breakfast Completed',
                        'description' => "$childName had " . strtolower($report->meals['breakfast']) . " of their breakfast",
                        'time' => '8:30 AM',
                        'timestamp' => 1 // For sorting
                    ]);
                }
                if (($report->meals['lunch'] ?? 'None') !== 'None') {
                    $todaysActivities->push([
                        'type' => 'meal',
                        'icon' => 'fas fa-utensils',
                        'title' => 'Lunch Completed',
                        'description' => "$childName had " . strtolower($report->meals['lunch']) . " of their lunch",
                        'time' => '12:00 PM',
                        'timestamp' => 3
                    ]);
                }
                if (($report->meals['snack'] ?? 'None') !== 'None') {
                    $todaysActivities->push([
                        'type' => 'meal',
                        'icon' => 'fas fa-cookie-bite',
                        'title' => 'Snack Time',
                        'description' => "$childName had " . strtolower($report->meals['snack']) . " of their snack",
                        'time' => '3:30 PM',
                        'timestamp' => 5
                    ]);
                }
            }

            // Nap
            if ($report->nap_duration > 0) {
                $todaysActivities->push([
                    'type' => 'nap',
                    'icon' => 'fas fa-bed',
                    'title' => 'Nap Time',
                    'description' => "$childName slept for {$report->nap_duration} minutes ({$report->nap_quality})",
                    'time' => '1:00 PM',
                    'timestamp' => 4
                ]);
            }

            // Activities
            if (isset($report->activities) && is_array($report->activities)) {
                foreach ($report->activities as $activity) {
                    $todaysActivities->push([
                        'type' => 'play',
                        'icon' => 'fas fa-palette',
                        'title' => 'Activity Time',
                        'description' => "$childName participated in $activity",
                        'time' => '10:00 AM',
                        'timestamp' => 2
                    ]);
                }
            }
        }

        // Sort by approximate time
        $todaysActivities = $todaysActivities->sortBy('timestamp')->values();

        return view('parent.dashboard', compact(
            'children', 
            'childrenCount', 
            'attendanceRate',
            'pendingPayment',
            'upcomingEventsCount',
            'caregiversCount',
            'healthRecordsCount',
            'todaysActivities'
        ));
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
        // Get unread notifications count
        $unreadCount = auth()->user()->unreadNotifications->count();

        // Fetch active children (for the filter buttons)
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->where('status', '!=', 'pending')
            ->get();

        // Get child IDs
        $childIds = $children->pluck('id');

        // Get filter parameters
        $childId = $request->input('child_id');
        $date = $request->input('date', now('Asia/Dhaka')->toDateString());

        // Base query
        $reportsQuery = \App\Models\DailyReport::whereIn('child_id', $childIds);

        // Apply child filter
        if ($childId && in_array($childId, $childIds->toArray())) {
            $reportsQuery->where('child_id', $childId);
        }

        // Apply date filter
        $reportsQuery->whereDate('report_date', $date);

        // Calculate statistics
        $statsQuery = clone $reportsQuery;
        $totalReports = $statsQuery->count();
        $completedReports = $statsQuery->where('status', 'completed')->count();
        $daysPresent = $statsQuery->distinct('report_date')->count('report_date');

        // Get all reports for additional stats
        $allReports = $statsQuery->get();

        // Calculate average nap duration
        $avgNapDuration = $allReports->avg('nap_duration') ?? 0;

        // Calculate total activities
        $totalActivities = 0;
        $activityCounts = [];
        foreach ($allReports as $report) {
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

        // Get top 5 activities
        arsort($activityCounts);
        $topActivities = array_slice($activityCounts, 0, 5, true);

        // Get recent reports
        $viewAll = $request->get('view_all', false);
        $recentReportsQuery = $reportsQuery->with(['child', 'caregiver'])->latest('report_date');
        $recentReports = $viewAll ? $recentReportsQuery->get() : $recentReportsQuery->take(10)->get();

        // Get latest teacher note
        $latestReport = $recentReports->first();
        $latestTeacherNote = $latestReport ? $latestReport->notes : 'No recent notes available.';

        return view('parent.reports', compact(
            'unreadCount',
            'children',
            'totalReports',
            'completedReports',
            'daysPresent',
            'totalActivities',
            'avgNapDuration',
            'topActivities',
            'recentReports',
            'viewAll',
            'date',
            'latestTeacherNote'
        ));
    }

    public function attendance(Request $request)
    {
        $unreadCount = auth()->user()->unreadNotifications->count();
        
        // Fetch active children (for the filter buttons)
        $children = \App\Models\Child::where('parent_id', auth()->id())
            ->where('status', '!=', 'pending')
            ->get();

        // Get child IDs
        $childIds = $children->pluck('id');

        // Get filter parameters
        $childId = $request->input('child_id');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Base query for attendance records
        $attendanceQuery = \App\Models\Attendance::whereIn('child_id', $childIds);

        // Apply child filter
        if ($childId && in_array($childId, $childIds->toArray())) {
            $attendanceQuery->where('child_id', $childId);
        }

        // Apply month and year filter for calendar
        $attendanceQuery->whereMonth('date', $month)->whereYear('date', $year);

        $attendanceRecords = $attendanceQuery->get();

        // Prepare calendar data
        $calendarData = [];
        foreach ($attendanceRecords as $record) {
            $day = \Carbon\Carbon::parse($record->date)->day;
            if (!isset($calendarData[$day])) {
                $calendarData[$day] = [];
            }
            $calendarData[$day][] = $record;
        }
        
        // Calculate attendance statistics for the selected period (month/year)
        $totalDaysInMonth = \Carbon\Carbon::create($year, $month, 1)->daysInMonth;
        $daysPresent = $attendanceRecords->where('status', 'present')->count();
        $daysAbsent = $attendanceRecords->where('status', 'absent')->count();
        $timesLate = $attendanceRecords->where('is_late', true)->count();
        $totalDays = $daysPresent + $daysAbsent; // Only count days where attendance was recorded

        $attendanceRate = $totalDays > 0 ? round(($daysPresent / $totalDays) * 100, 2) : 0;
        
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
                'medications' => function($query) {
                    $query->where('status', 'active')
                          ->where(function($q) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', now()->toDateString());
                          });
                }, 
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

    public function downloadReport($id)
    {
        // Fetch the report with relationships
        $report = \App\Models\DailyReport::with(['child', 'caregiver'])->findOrFail($id);
        
        // Verify the report belongs to the authenticated parent's child
        $child = \App\Models\Child::where('parent_id', auth()->id())->findOrFail($report->child_id);
        
        // Check if report is completed
        if ($report->status !== 'completed') {
            return redirect()->route('parent.reports')->with('error', 'Only completed reports can be downloaded.');
        }
        
        // Prepare data for PDF
        $caregiver = $report->caregiver;
        
        // Load PDF view
        $pdf = Pdf::loadView('parent.pdf.daily-report', compact('report', 'child', 'caregiver'));
        
        // Generate filename
        $filename = 'daily-report-' . $child->first_name . '-' . \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
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
