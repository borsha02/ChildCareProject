<?php
require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\AdminController;


// Public pages
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/aboutus', fn() => view('pages.about'))->name('about');
Route::get('/contact', fn() => view('pages.contact'))->name('contact');
Route::get('/activities', fn() => view('pages.activities'))->name('activities');
Route::get('/programs', fn() => view('pages.programs'))->name('programs');
Route::get('/career', fn() => view('pages.career'))->name('career');
Route::post('/career/submit', [App\Http\Controllers\JobApplicationController::class, 'store'])->name('career.submit');



// Admin module
Route::middleware('auth')->prefix('admin')->group(function (){
    // Dashboard & Analytics
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/activities', [AdminController::class, 'activities'])->name('admin.activities');

    // User Management (Feature #2, #3)
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    Route::post('/users/{id}/assign-role', [AdminController::class, 'assignRole'])->name('admin.users.assign-role');

    // Child Records (Feature #4)
    Route::get('/children', [AdminController::class, 'children'])->name('admin.children');
    Route::post('/children/create', [AdminController::class, 'createChild'])->name('admin.children.create');
    Route::put('/children/{id}', [AdminController::class, 'updateChild'])->name('admin.children.update');
    Route::delete('/children/{id}', [AdminController::class, 'deleteChild'])->name('admin.children.delete');
    Route::post('/children/{id}/approve', [AdminController::class, 'approveChild'])->name('admin.children.approve');
    Route::post('/children/{id}/reject', [AdminController::class, 'rejectChild'])->name('admin.children.reject');
    Route::post('/children/{id}/assign-caregiver', [AdminController::class, 'assignCaregiver'])->name('admin.children.assign');
    Route::delete('/children/{id}/remove-caregiver', [AdminController::class, 'removeCaregiver'])->name('admin.children.remove-caregiver');
    Route::post('/children/{id}/reactivate', [AdminController::class, 'reactivate'])->name('admin.children.reactivate');


    // Staff Management (Feature #5)
    Route::get('/staff', [AdminController::class, 'staff'])->name('admin.staff');
    Route::get('/job-applications', [AdminController::class, 'jobApplications'])->name('admin.job-applications');
    Route::delete('/job-applications/{id}', [AdminController::class, 'deleteJobApplication'])->name('admin.job-applications.delete');
    Route::post('/staff/create', [AdminController::class, 'createStaff'])->name('admin.staff.create');
    Route::put('/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.staff.update');
    Route::delete('/staff/{id}', [AdminController::class, 'deleteStaff'])->name('admin.staff.delete');
    Route::get('/ratings', [AdminController::class, 'ratings'])->name('admin.ratings');
    Route::post('/staff/assign', [AdminController::class, 'assignStaffToChild'])->name('admin.staff.assign');
    Route::post('/job-applications/{id}/reject', [AdminController::class, 'rejectJobApplication'])->name('admin.jobs.reject');
    Route::get('/staff/{id}/ratings', [AdminController::class, 'staffRatings'])->name('admin.staff.ratings');
    Route::post('/leave-requests/{id}/approve', [AdminController::class, 'approveLeave'])->name('admin.leave.approve');
    Route::post('/leave-requests/{id}/deny', [AdminController::class, 'denyLeave'])->name('admin.leave.deny');

    // Attendance (Feature #6)
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
    Route::get('/attendance/export', [AdminController::class, 'exportAttendance'])->name('admin.attendance.export');
    Route::post('/attendance/update', [AdminController::class, 'updateAttendance'])->name('admin.attendance.update'); // New route

    // Daily Reports (Feature #7)
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/export-pdf', [AdminController::class, 'exportReportsPdf'])->name('admin.reports.export.pdf');
    Route::get('/reports/{id}', [AdminController::class, 'viewDailyReport'])->name('admin.reports.view');

    // Billing & Invoices (Feature #8)
    Route::get('/invoices', [AdminController::class, 'invoices'])->name('admin.invoices');
    Route::post('/invoices/generate', [AdminController::class, 'generateInvoice'])->name('admin.invoices.generate');
    Route::put('/invoices/{id}', [AdminController::class, 'updateInvoice'])->name('admin.invoices.update');
    Route::get('/invoices/{id}/download', [AdminController::class, 'downloadInvoice'])->name('admin.invoices.download');



    // Payment Approvals (Feature #11)
    Route::get('/pending', [AdminController::class, 'pendingActions'])->name('admin.pending');
    Route::get('/pending-payments', [AdminController::class, 'pendingPayments'])->name('admin.payments.pending');
    Route::post('/payments/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payments.approve');
    Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payments.reject');

    
    // Settings & Configurations (Feature #13)
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/classrooms', [AdminController::class, 'storeClassroom'])->name('admin.classrooms.store');
    Route::put('/classrooms/{id}', [AdminController::class, 'updateClassroom'])->name('admin.classrooms.update');
    Route::delete('/classrooms/{id}', [AdminController::class, 'deleteClassroom'])->name('admin.classrooms.delete');

    // Communication (Feature #11)
    Route::get('/communication', [AdminController::class, 'communicationLogs'])->name('admin.communication');
    Route::redirect('/communication-logs', '/admin/communication'); // Redirect old URL
    Route::post('/communication/send', [AdminController::class, 'sendMessage'])->name('admin.communication.send');
    Route::get('/communication/conversation/{userId}', [AdminController::class, 'getConversation'])->name('admin.communication.conversation');
    Route::get('/messages/{id}', [AdminController::class, 'viewMessage'])->name('admin.messages.view');
    Route::put('/messages/{id}', [AdminController::class, 'editMessage'])->name('admin.messages.edit');
    Route::delete('/messages/{id}', [AdminController::class, 'deleteMessage'])->name('admin.messages.delete');

    // Events (Feature #15)
    Route::get('/events', [App\Http\Controllers\AdminEventController::class, 'index'])->name('admin.events.index');
    Route::get('/events/calendar', [App\Http\Controllers\AdminEventController::class, 'calendar'])->name('admin.events.calendar');
    Route::get('/events/create', [App\Http\Controllers\AdminEventController::class, 'create'])->name('admin.events.create');
    Route::post('/events', [App\Http\Controllers\AdminEventController::class, 'store'])->name('admin.events.store');
    Route::post('/events/bulk', [App\Http\Controllers\AdminEventController::class, 'bulkStore'])->name('admin.events.bulk-store');
    Route::get('/events/{event}/edit', [App\Http\Controllers\AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/events/{event}', [App\Http\Controllers\AdminEventController::class, 'update'])->name('admin.events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\AdminEventController::class, 'destroy'])->name('admin.events.delete');
});


// Caregiver module
Route::middleware('auth')->prefix('caregiver')->group(function () {
    Route::get('/dashboard', [CaregiverController::class, 'dashboard'])->name('caregiver.dashboard');
    Route::get('/assigned-children', [CaregiverController::class, 'assignedChildren'])->name('caregiver.assigned');
    Route::get('/attendance', [CaregiverController::class, 'attendance'])->name('caregiver.attendance');
    Route::post('/attendance', [CaregiverController::class, 'storeAttendance'])->name('caregiver.attendance.store');
    Route::get('/daily-reports/check', [CaregiverController::class, 'checkDailyReport'])->name('caregiver.daily-reports.check');
    Route::get('/daily-reports', [CaregiverController::class, 'dailyReports'])->name('caregiver.daily-reports');
    Route::post('/daily-reports', [CaregiverController::class, 'storeDailyReport'])->name('caregiver.reports.store');
    Route::get('/health-records', [CaregiverController::class, 'healthRecords'])->name('caregiver.health');
    Route::get('/health-records/{id}', [CaregiverController::class, 'showChildHealth'])->name('caregiver.health.show');
    Route::get('/messages', [CaregiverController::class, 'messages'])->name('caregiver.messages');
    Route::post('/messages', [CaregiverController::class, 'sendMessage'])->name('caregiver.messages.send');
    Route::get('/messages/conversation/{parentId}', [CaregiverController::class, 'getConversation'])->name('caregiver.messages.conversation');
    Route::post('/messages/{id}/read', [CaregiverController::class, 'markAsRead'])->name('caregiver.messages.read');

    Route::get('/ratings', [CaregiverController::class, 'ratings'])->name('caregiver.ratings');
    Route::get('/schedule', [CaregiverController::class, 'schedule'])->name('caregiver.schedule');
    Route::get('/events', [CaregiverController::class, 'events'])->name('caregiver.events');
    Route::get('/notifications', [CaregiverController::class, 'notifications'])->name('caregiver.notifications');
    Route::post('/notifications/mark-read', [CaregiverController::class, 'markAllNotificationsRead'])->name('caregiver.notifications.mark-all');
    Route::post('/notifications/{id}/mark-read', [CaregiverController::class, 'markNotificationRead'])->name('caregiver.notifications.mark-read');
    Route::delete('/notifications/{id}', [CaregiverController::class, 'deleteNotification'])->name('caregiver.notifications.delete');
    Route::get('/leave-requests', [CaregiverController::class, 'leaveRequests'])->name('caregiver.leave');
    Route::post('/leave-requests/store', [CaregiverController::class, 'storeLeaveRequest'])->name('caregiver.leave.store');
    Route::delete('/leave-requests/{id}', [CaregiverController::class, 'destroyLeaveRequest'])->name('caregiver.leave.delete');
    Route::get('/caregiver/settings', [App\Http\Controllers\CaregiverController::class, 'settings'])->name('caregiver.settings');
    Route::post('/caregiver/settings/update', [App\Http\Controllers\CaregiverController::class, 'updateSettings'])->name('caregiver.settings.update');
    Route::post('/caregiver/settings/password', [App\Http\Controllers\CaregiverController::class, 'updatePassword'])->name('caregiver.settings.password');
});



// Parent module
Route::middleware('auth')->prefix('parent')->group(function (){
    Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/child-profile', [ParentController::class, 'childProfile'])->name('parent.child-profile');
    Route::get('/caregivers', [ParentController::class, 'caregivers'])->name('parent.caregivers');
    Route::post('/ratings', [ParentController::class, 'storeRating'])->name('parent.ratings.store');
    Route::post('/child-profile', [ParentController::class, 'storeChild'])->name('parent.child-profile.store');
    Route::put('/child-profile/{id}', [ParentController::class, 'updateChild'])->name('parent.child-profile.update');
    Route::delete('/child-profile/{id}', [ParentController::class, 'deleteChild'])->name('parent.child-profile.delete');
    Route::get('/reports', [ParentController::class, 'reports'])->name('parent.reports');
    Route::get('/reports/{id}/download', [ParentController::class, 'downloadReport'])->name('parent.reports.download');
    Route::get('/attendance', [ParentController::class, 'attendance'])->name('parent.attendance');
    Route::get('/invoices', [ParentController::class, 'invoices'])->name('parent.invoice');
    Route::get('/invoices/{id}/download', [ParentController::class, 'downloadInvoice'])->name('parent.invoices.download');
    Route::get('/health', [ParentController::class, 'health'])->name('parent.health');
    Route::get('/health/vaccinations', [ParentController::class, 'vaccinations'])->name('parent.vaccinations');
    Route::get('/health/medications', [ParentController::class, 'medications'])->name('parent.medications');
    Route::post('/health/vaccination', [ParentController::class, 'storeVaccination'])->name('parent.health.vaccination.store');
    Route::post('/health/medication', [ParentController::class, 'storeMedication'])->name('parent.health.medication.store');
    Route::put('/health/medication/{id}', [ParentController::class, 'updateMedication'])->name('parent.health.medication.update');
    Route::delete('/health/medication/{id}', [ParentController::class, 'deleteMedication'])->name('parent.health.medication.delete');
    Route::post('/health/record', [ParentController::class, 'storeHealthRecord'])->name('parent.health.record.store');
    Route::put('/health/record/{id}', [ParentController::class, 'updateHealthRecord'])->name('parent.health.record.update');
    Route::delete('/health/record/{id}', [ParentController::class, 'deleteHealthRecord'])->name('parent.health.record.delete');
    Route::put('/health/allergies', [ParentController::class, 'updateAllergies'])->name('parent.health.allergies.update');
    Route::post('/health/checkup', [ParentController::class, 'storeCheckup'])->name('parent.health.checkup.store');
    Route::put('/health/checkup/{id}', [ParentController::class, 'updateCheckup'])->name('parent.health.checkup.update');
    Route::delete('/health/checkup/{id}', [ParentController::class, 'deleteCheckup'])->name('parent.health.checkup.delete');
    Route::get('/messages', [ParentController::class, 'messages'])->name('parent.messages');
    Route::post('/messages/send', [ParentController::class, 'sendMessage'])->name('parent.messages.send');
    Route::get('/messages/conversation/{caregiverId}', [ParentController::class, 'getConversation'])->name('parent.messages.conversation');
    Route::post('/messages/{id}/mark-read', [ParentController::class, 'markAsRead'])->name('parent.messages.mark-read');
    Route::get('/notifications', [ParentController::class, 'notifications'])->name('parent.notifications');
    Route::post('/notifications/mark-read', [ParentController::class, 'markAllNotificationsRead'])->name('parent.notifications.mark-all');
    Route::post('/notifications/{id}/mark-read', [ParentController::class, 'markNotificationRead'])->name('parent.notifications.mark-read');
    Route::delete('/notifications/{id}', [ParentController::class, 'deleteNotification'])->name('parent.notifications.delete');
    Route::get('/events', [ParentController::class, 'events'])->name('parent.events');
    Route::post('/events/register', [ParentController::class, 'registerEvent'])->name('parent.events.register');
    Route::get('/settings', [ParentController::class, 'settings'])->name('parent.settings');
    Route::post('/settings', [ParentController::class, 'updateSettings'])->name('parent.settings.update');
    Route::post('/settings/password', [ParentController::class, 'updatePassword'])->name('parent.settings.password');
    Route::get('/help', [ParentController::class, 'help'])->name('parent.help');
    Route::get('/caregivers', [ParentController::class, 'caregivers'])->name('parent.caregivers');

    // Payment Initiation (Needs Auth)
    Route::post('/invoice/{id}/pay', [App\Http\Controllers\PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/receipt/{transactionId}', [App\Http\Controllers\PaymentController::class, 'downloadReceipt'])->name('payment.receipt.download');
});

// Payment Callbacks (Public - No Auth required for callback handling)
Route::post('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [App\Http\Controllers\PaymentController::class, 'fail'])->name('payment.fail');
Route::post('/payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/ipn', [App\Http\Controllers\PaymentController::class, 'ipn'])->name('payment.ipn');

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Debug Route
Route::get('/debug-auth', function () {
    return response()->json([
        'is_logged_in' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_driver' => config('session.driver'),
        'session_lifetime' => config('session.lifetime'),
        'session_secure' => config('session.secure'),
        'session_domain' => config('session.domain'),
    ]);
});
