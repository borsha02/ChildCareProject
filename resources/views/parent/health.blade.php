<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/health.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Little Stars Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <h4>{{Auth::user()->name}}</h4>
                        <p>Parent Account</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('parent.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('parent.child-profile') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Profile</span>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('parent.reports') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('parent.messages') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        @php
                            $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
                        <span>Notifications</span>
                        <span class="badge">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <a href="{{ route('parent.health') }}" class="nav-item active">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="nav-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Assigned Caregivers</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="{{ route('parent.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('parent.help') }}" class="nav-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                    <a href="{{ route('logout') }}" class="nav-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('parent.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Health Records</h1>
                </div>
                <div class="top-bar-actions">
                 <!--   <div class="search-box">
                        <input type="text" placeholder="Search records...">
                        <i class="fas fa-search"></i>
                    </div> -->
                    <a href="{{ route('parent.notifications') }}" class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
                    </a>
                    <button class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            <div class="content-area">
                <div class="health-container">
                    <!-- Child Selector -->
                    <div class="child-selector-section">
                        @forelse($children as $index => $child)
                        <div class="child-tab {{ $index === 0 ? 'active' : '' }}" data-child="{{ $child->id }}">
                            <div class="child-avatar" style="background: linear-gradient(135deg, {{ $index % 2 === 0 ? '#10b981, #059669' : '#3b82f6, #2563eb' }});">
                                {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                            </div>
                            <div class="child-info">
                                <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                                <p>{{ \Carbon\Carbon::parse($child->dob)->age }} years old</p>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state" style="padding: 2rem; text-align: center;">
                            <i class="fas fa-child" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                            <h3 style="color: #6b7280;">No Children Added</h3>
                            <p style="color: #9ca3af;">Add a child profile to view health records</p>
                            <a href="{{ route('parent.child-profile') }}" class="add-btn" style="margin-top: 1rem; display: inline-block;">
                                <i class="fas fa-plus"></i> Add Child
                            </a>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pending Approval Message -->
                    <div id="pendingMessageContainer" style="display: {{ $children->first() && $children->first()->status === 'pending' ? 'flex' : 'none' }}; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; background: white; border-radius: 12px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center;">
                        <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-user-clock" style="font-size: 40px; color: #dc2626;"></i>
                        </div>
                        <h2 style="color: #1f2937; margin-bottom: 0.5rem; font-size: 24px;">Registration Pending</h2>
                        <p style="color: #6b7280; max-width: 400px; margin-bottom: 2rem; font-size: 16px;">
                            This child's registration is currently under review by the administration. Health records will be available once the registration is approved.
                        </p>
                        <div style="display: flex; gap: 10px; font-size: 14px; color: #4b5563; background: #f3f4f6; padding: 10px 20px; border-radius: 20px;">
                            <i class="fas fa-info-circle" style="color: #4f46e5; margin-top: 2px;"></i>
                            <span>You will receive a notification when approved.</span>
                        </div>
                    </div>

                    <div id="healthContentContainer" style="display: {{ !$children->first() || $children->first()->status !== 'pending' ? 'block' : 'none' }}">
                        <!-- Health Overview Cards -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h2 style="font-size: 20px; color: #1f2937; font-weight: 600;">Health Overview</h2>
                            <div style="display: flex; gap: 10px;">
                                <button class="add-btn" onclick="openHealthRecordModal()" style="background: linear-gradient(135deg, #10b981, #059669);">
                                    <i class="fas fa-edit"></i> Edit Record
                                </button>
                                <button class="add-btn" onclick="openHealthRecordModal('add')">
                                    <i class="fas fa-plus"></i> Add Health Record
                                </button>
                            </div>
                        </div>
                        <div class="health-stats">
                            <div class="health-card">
                                <div class="health-icon blood">
                                    <i class="fas fa-tint"></i>
                                </div>
                                <div class="health-details">
                                    <h3 id="bloodType">{{ $children->first()->blood_group ?? 'N/A' }}</h3>
                                    <p>Blood Type</p>
                                </div>
                            </div>
                            <div class="health-card">
                                <div class="health-icon weight">
                                    <i class="fas fa-weight"></i>
                                </div>
                                <div class="health-details">
                                    <h3 id="weightValue">
                                        @if($children->first() && $children->first()->healthRecords->isNotEmpty())
                                            {{ $children->first()->healthRecords->first()->weight }} kg
                                        @else
                                            N/A
                                        @endif
                                    </h3>
                                    <p>Weight</p>
                                </div>
                            </div>
                            <div class="health-card">
                                <div class="health-icon height">
                                    <i class="fas fa-ruler-vertical"></i>
                                </div>
                                <div class="health-details">
                                    <h3 id="heightValue">
                                        @if($children->first() && $children->first()->healthRecords->isNotEmpty())
                                            {{ $children->first()->healthRecords->first()->height }} cm
                                        @else
                                            N/A
                                        @endif
                                    </h3>
                                    <p>Height</p>
                                </div>
                            </div>
                            <div class="health-card">
                                <div class="health-icon checkup">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <div class="health-details">
                                    <h3 id="lastCheckupDate">
                                        @if($children->first() && $children->first()->healthRecords->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($children->first()->healthRecords->first()->record_date)->format('M j') }}
                                        @else
                                            N/A
                                        @endif
                                    </h3>
                                    <p>Last Checkup</p>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content Grid -->
                        <div class="content-grid">
                            <!-- Vaccination Records -->
                            <div class="card">
                                <div class="card-header">
                                    <h2><i class="fas fa-syringe"></i> Vaccination Records</h2>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('parent.vaccinations') }}" class="view-all-btn">View All</a>
                                        <button class="add-btn" onclick="openVaccinationModal()">
                                            <i class="fas fa-plus"></i> Add Record
                                        </button>
                                    </div>
                                </div>
                                <div class="vaccination-list" id="vaccinationList">
                                    @if($children->isNotEmpty() && $children->first()->vaccinations->isNotEmpty())
                                        @foreach($children->first()->vaccinations as $vaccination)
                                        <div class="vaccination-item {{ $vaccination->status }}">
                                            <div class="vaccine-icon">
                                                <i class="fas {{ $vaccination->status === 'completed' ? 'fa-check-circle' : ($vaccination->status === 'upcoming' ? 'fa-clock' : 'fa-exclamation-triangle') }}"></i>
                                            </div>
                                            <div class="vaccine-info">
                                                <h4>{{ $vaccination->vaccine_name }}</h4>
                                                <p>{{ $vaccination->description ?? 'No description' }}</p>
                                                <span class="vaccine-date">
                                                    @if($vaccination->administered_date)
                                                        Administered: {{ \Carbon\Carbon::parse($vaccination->administered_date)->format('F j, Y') }}
                                                    @elseif($vaccination->scheduled_date)
                                                        Scheduled: {{ \Carbon\Carbon::parse($vaccination->scheduled_date)->format('F j, Y') }}
                                                    @endif
                                                </span>
                                            </div>
                                            <span class="vaccine-status {{ $vaccination->status }}">{{ ucfirst($vaccination->status) }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state" style="padding: 2rem; text-align: center;">
                                        <i class="fas fa-syringe" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                        <p style="color: #6b7280;">No vaccination records found</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Allergies & Medical Conditions -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-allergies"></i> Allergies & Conditions</h2>
                                <button class="add-btn" onclick="openAllergiesModal()">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                            <div class="medical-info">
                                <div class="info-section">
                                    <h3>Known Allergies</h3>
                                    <div class="allergy-tags" id="allergyTags">
                                        @php
                                            $allergies = $children->first() && $children->first()->allergies ? explode(',', $children->first()->allergies) : [];
                                        @endphp
                                        @forelse($allergies as $allergy)
                                            <span class="allergy-tag">
                                                <i class="fas fa-exclamation-triangle"></i> {{ trim($allergy) }}
                                            </span>
                                        @empty
                                            <span class="no-data">None reported</span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="info-section">
                                    <h3>Medical Conditions</h3>
                                    <div class="condition-list" id="conditionList">
                                        @if($children->first() && $children->first()->medical_notes)
                                            <div class="condition-item">
                                                <i class="fas fa-notes-medical"></i>
                                                <div>
                                                    <h4>Medical History</h4>
                                                    <p>{{ $children->first()->medical_notes }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="no-data">No conditions reported</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-section">
                                    <h3>Emergency Contact</h3>
                                    <div class="emergency-contact" id="emergencyContact">
                                        <i class="fas fa-phone-alt"></i>
                                        <div>
                                            <h4>Primary Contact</h4>
                                            <p>{{ $children->first()->emergency_contact ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medication Records -->
                        <div class="card" style="margin-bottom: 30px;">
                            <div class="card-header">
                                <h2><i class="fas fa-pills"></i> Current Medications</h2>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('parent.medications') }}" class="view-all-btn">View All</a>
                                    <button class="add-btn" onclick="openMedicationModal()">
                                        <i class="fas fa-plus"></i> Add Medication
                                    </button>
                                </div>
                            </div>
                            <table class="medication-table">
                                <thead>
                                    <tr>
                                        <th>Medication</th>
                                        <th>Dosage</th>
                                        <th>Frequency</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="medicationTableBody">
                                    @if($children->isNotEmpty() && $children->first()->medications->isNotEmpty())
                                        @foreach($children->first()->medications as $medication)
                                        <tr>
                                            <td>
                                                <div class="medication-name">
                                                    <i class="fas fa-capsules"></i>
                                                    <span>{{ $medication->medication_name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $medication->dosage }}</td>
                                            <td>{{ $medication->frequency }}</td>
                                            <td>{{ \Carbon\Carbon::parse($medication->start_date)->format('M j, Y') }}</td>
                                            <td>{{ $medication->end_date ? \Carbon\Carbon::parse($medication->end_date)->format('M j, Y') : 'Ongoing' }}</td>
                                            <td><span class="status-badge {{ $medication->status }}">{{ ucfirst($medication->status) }}</span></td>
                                            <td>
                                                <button class="action-icon-btn" title="Edit" onclick="editMedication({{ $medication->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-icon-btn" title="Delete" onclick="deleteMedication({{ $medication->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                                <i class="fas fa-pills" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem; display: block;"></i>
                                                <p style="color: #6b7280;">No medications found</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Health Checkup History -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-notes-medical"></i> Checkup History</h2>
                                <button class="add-btn" onclick="openCheckupModal()">
                                    <i class="fas fa-plus"></i> Add Checkup
                                </button>
                            </div>
                            <div class="checkup-timeline" id="checkupTimeline">
                                @php
                                    $allRecords = collect();
                                    
                                    if($children->isNotEmpty()) {
                                        $child = $children->first();
                                        
                                        // Add checkups
                                        if($child->checkups) {
                                            $allRecords = $allRecords->concat($child->checkups->map(function($item) {
                                                $item->type = 'checkup';
                                                $item->sort_date = $item->checkup_date;
                                                return $item;
                                            }));
                                        }
                                        
                                        // Add health records
                                        if($child->healthRecords) {
                                            $allRecords = $allRecords->concat($child->healthRecords->map(function($item) {
                                                $item->type = 'health_record';
                                                $item->sort_date = $item->record_date;
                                                return $item;
                                            }));
                                        }
                                        
                                        // Sort by date desc
                                        $allRecords = $allRecords->sortByDesc('sort_date');
                                    }
                                @endphp

                                @if($allRecords->isNotEmpty())
                                    @foreach($allRecords as $record)
                                        <div class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                @if($record->type === 'checkup')
                                                    <div class="timeline-header">
                                                        <h4>{{ $record->checkup_type }}</h4>
                                                        <div style="display: flex; gap: 10px; align-items: center;">
                                                            <span class="timeline-date">{{ \Carbon\Carbon::parse($record->checkup_date)->format('M j, Y') }}</span>
                                                            <button class="action-icon-btn small" title="Edit" onclick="editCheckup({{ $record->id }})">
                                                                <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                                            </button>
                                                            <button class="action-icon-btn small" title="Delete" onclick="deleteCheckup({{ $record->id }})">
                                                                <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="timeline-doctor">{{ $record->doctor_name }}{{ $record->doctor_specialty ? ' - ' . $record->doctor_specialty : '' }}</p>
                                                    @if($record->weight || $record->height || $record->bmi)
                                                    <div class="timeline-details">
                                                        @if($record->weight) <div class="detail-item"><span class="detail-label">Weight:</span><span class="detail-value">{{ $record->weight }} kg</span></div> @endif
                                                        @if($record->height) <div class="detail-item"><span class="detail-label">Height:</span><span class="detail-value">{{ $record->height }} cm</span></div> @endif
                                                        @if($record->bmi) <div class="detail-item"><span class="detail-label">BMI:</span><span class="detail-value">{{ $record->bmi }}</span></div> @endif
                                                    </div>
                                                    @endif
                                                    @if($record->notes)
                                                    <p class="timeline-notes"><strong>Notes:</strong> {{ $record->notes }}</p>
                                                    @endif
                                                @else
                                                    {{-- Health Record Item --}}
                                                    <div class="timeline-header">
                                                        <h4>Health Log</h4>
                                                        <div style="display: flex; gap: 10px; align-items: center;">
                                                            <span class="timeline-date">{{ \Carbon\Carbon::parse($record->record_date)->format('M j, Y') }}</span>
                                                            <button class="action-icon-btn small" title="Edit" onclick="editHealthRecord({{ $record->id }})">
                                                                <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                                            </button>
                                                            <button class="action-icon-btn small" title="Delete" onclick="deleteHealthRecord({{ $record->id }})">
                                                                <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-details">
                                                        @if($record->weight) <div class="detail-item"><span class="detail-label">Weight:</span><span class="detail-value">{{ $record->weight }} kg</span></div> @endif
                                                        @if($record->height) <div class="detail-item"><span class="detail-label">Height:</span><span class="detail-value">{{ $record->height }} cm</span></div> @endif
                                                    </div>
                                                    @if($record->notes)
                                                    <p class="timeline-notes"><strong>Notes:</strong> {{ $record->notes }}</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state" style="padding: 2rem; text-align: center;">
                                        <i class="fas fa-notes-medical" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                        <p style="color: #6b7280;">No health records found</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Vaccination Modal -->
    <div class="modal" id="vaccinationModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Vaccination Record</h2>
                <button class="close-modal" onclick="closeVaccinationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('parent.health.vaccination.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="vaccination_child_id" value="{{ $children->first()->id ?? '' }}">
                    
                    <div class="form-group">
                        <label for="vaccine_name">Vaccine Name <span style="color: red;">*</span></label>
                        <input type="text" id="vaccine_name" name="vaccine_name" required placeholder="e.g., MMR Vaccine">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="e.g., Measles, Mumps, Rubella"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status <span style="color: red;">*</span></label>
                            <select id="status" name="status" required>
                                <option value="">Select status</option>
                                <option value="completed">Completed</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="administered_date">Administered Date</label>
                            <input type="date" id="administered_date" name="administered_date">
                        </div>
                        <div class="form-group">
                            <label for="scheduled_date">Scheduled Date</label>
                            <input type="date" id="scheduled_date" name="scheduled_date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vaccination_notes">Notes</label>
                        <textarea id="vaccination_notes" name="notes" placeholder="Additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeVaccinationModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Medication Modal -->
    <div class="modal" id="medicationModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="medicationModalTitle">Add Medication</h2>
                <button class="close-modal" onclick="closeMedicationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="medicationForm" action="{{ route('parent.health.medication.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="medication_method" value="POST">
                <input type="hidden" name="medication_id" id="medication_id" value="">
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="medication_child_id" value="{{ $children->first()->id ?? '' }}">
                    
                    <div class="form-group">
                        <label for="medication_name">Medication Name <span style="color: red;">*</span></label>
                        <input type="text" id="medication_name" name="medication_name" required placeholder="e.g., Albuterol Inhaler">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dosage">Dosage <span style="color: red;">*</span></label>
                            <input type="text" id="dosage" name="dosage" required placeholder="e.g., 2 puffs">
                        </div>
                        <div class="form-group">
                            <label for="frequency">Frequency <span style="color: red;">*</span></label>
                            <input type="text" id="frequency" name="frequency" required placeholder="e.g., As needed">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date <span style="color: red;">*</span></label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="medication_status">Status <span style="color: red;">*</span></label>
                        <select id="medication_status" name="status" required>
                            <option value="">Select status</option>
                            <option value="active" selected>Active</option>
                            <option value="completed">Completed</option>
                            <option value="discontinued">Discontinued</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medication_notes">Notes</label>
                        <textarea id="medication_notes" name="notes" placeholder="Additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeMedicationModal()">Cancel</button>
                    <button type="submit" class="btn-submit" id="medicationSubmitBtn">
                        <i class="fas fa-save"></i> Save Medication
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Allergies & Conditions Modal -->
    <div class="modal" id="allergiesModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Allergies & Medical Conditions</h2>
                <button class="close-modal" onclick="closeAllergiesModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('parent.health.allergies.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="allergies_child_id" value="{{ $children->first()->id ?? '' }}">
                    
                    <div class="form-group">
                        <label for="allergies">Known Allergies</label>
                        <textarea id="allergies" name="allergies" rows="3" placeholder="Enter allergies separated by commas (e.g., Peanuts, Dairy, Penicillin)">{{ $children->first()->allergies ?? '' }}</textarea>
                        <small style="color: #6b7280; font-size: 12px;">Separate multiple allergies with commas</small>
                    </div>

                    <div class="form-group">
                        <label for="medical_notes">Medical Conditions / History</label>
                        <textarea id="medical_notes" name="medical_notes" rows="4" placeholder="Enter any medical conditions, chronic illnesses, or important medical history">{{ $children->first()->medical_notes ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Number</label>
                        <input type="tel" id="emergency_contact" name="emergency_contact" value="{{ $children->first()->emergency_contact ?? '' }}" placeholder="e.g., +880 1234-567890">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAllergiesModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Checkup Modal -->
    <div class="modal" id="checkupModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="checkupModalTitle">Add Checkup Record</h2>
                <button class="close-modal" onclick="closeCheckupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="checkupForm" action="{{ route('parent.health.checkup.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="checkup_method" value="POST">
                <input type="hidden" name="checkup_id" id="checkup_id" value="">
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="checkup_child_id" value="{{ $children->first()->id ?? '' }}">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="checkup_type">Checkup Type <span style="color: red;">*</span></label>
                            <select id="checkup_type" name="checkup_type" required>
                                <option value="">Select type</option>
                                <option value="Routine Checkup">Routine Checkup</option>
                                <option value="Vaccination Visit">Vaccination Visit</option>
                                <option value="Sick Visit">Sick Visit</option>
                                <option value="Specialist Consultation">Specialist Consultation</option>
                                <option value="Dental Checkup">Dental Checkup</option>
                                <option value="Vision Test">Vision Test</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="checkup_date">Date <span style="color: red;">*</span></label>
                            <input type="date" id="checkup_date" name="checkup_date" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="doctor_name">Doctor Name</label>
                            <input type="text" id="doctor_name" name="doctor_name" placeholder="Dr. Name">
                        </div>
                        <div class="form-group">
                            <label for="doctor_specialty">Specialty</label>
                            <input type="text" id="doctor_specialty" name="doctor_specialty" placeholder="e.g. Pediatrician">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="checkup_weight">Weight (kg)</label>
                            <input type="number" id="checkup_weight" name="weight" step="0.01" min="0" max="999.99" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="checkup_height">Height (cm)</label>
                            <input type="number" id="checkup_height" name="height" step="0.01" min="0" max="999.99" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="checkup_bmi">BMI</label>
                            <input type="number" id="checkup_bmi" name="bmi" step="0.01" min="0" max="99.99" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="checkup_notes">Notes / Diagnosis</label>
                        <textarea id="checkup_notes" name="notes" placeholder="Checkup results, diagnosis, or instructions"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeCheckupModal()">Cancel</button>
                    <button type="submit" class="btn-submit" id="checkupSubmitBtn">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Health Record Modal -->
    <div class="modal" id="healthRecordModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="healthRecordModalTitle">Add Health Record</h2>
                <button class="close-modal" onclick="closeHealthRecordModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="healthRecordForm" action="{{ route('parent.health.record.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="health_record_method" value="POST">
                <input type="hidden" name="health_record_id" id="health_record_id" value="">
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="health_record_child_id" value="{{ $children->first()->id ?? '' }}">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight">Weight (kg) <span style="color: red;">*</span></label>
                            <input type="number" id="weight" name="weight" step="0.01" min="0" max="999.99" required placeholder="e.g., 18.5">
                        </div>
                        <div class="form-group">
                            <label for="height">Height (cm) <span style="color: red;">*</span></label>
                            <input type="number" id="height" name="height" step="0.01" min="0" max="999.99" required placeholder="e.g., 110">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="record_date">Record Date <span style="color: red;">*</span></label>
                        <input type="date" id="record_date" name="record_date" required>
                    </div>

                    <div class="form-group">
                        <label for="health_notes">Notes</label>
                        <textarea id="health_notes" name="notes" placeholder="Additional notes about this health record"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeHealthRecordModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> <span id="healthRecordSubmitText">Save Record</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Inject children data
        const childrenData = @json($children);

        // Child tab switching
        document.querySelectorAll('.child-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.child-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Get selected child ID
                const childId = parseInt(this.getAttribute('data-child'));
                
                // Find child data
                const child = childrenData.find(c => c.id === childId);
                
                if (child) {
                    const pendingContainer = document.getElementById('pendingMessageContainer');
                    const contentContainer = document.getElementById('healthContentContainer');
                    
                    if (child.status === 'pending') {
                        pendingContainer.style.display = 'flex';
                        if (contentContainer) contentContainer.style.display = 'none';
                        return; 
                    } else {
                        pendingContainer.style.display = 'none';
                        if (contentContainer) contentContainer.style.display = 'block';
                    }
                    // Update blood type
                    document.getElementById('bloodType').textContent = child.blood_group || 'N/A';
                    
                    // Update Health Stats (weight, height, last checkup)
                    const weightEl = document.getElementById('weightValue');
                    const heightEl = document.getElementById('heightValue');
                    const checkupEl = document.getElementById('lastCheckupDate');
                    
                    if (child.health_records && child.health_records.length > 0) {
                        const latestRecord = child.health_records[0];
                        weightEl.textContent = latestRecord.weight ? `${latestRecord.weight} kg` : 'N/A';
                        heightEl.textContent = latestRecord.height ? `${latestRecord.height} cm` : 'N/A';
                        
                        if (latestRecord.record_date) {
                            const date = new Date(latestRecord.record_date);
                            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            checkupEl.textContent = `${monthNames[date.getMonth()]} ${date.getDate()}`;
                        } else {
                            checkupEl.textContent = 'N/A';
                        }
                    } else {
                        weightEl.textContent = 'N/A';
                        heightEl.textContent = 'N/A';
                        checkupEl.textContent = 'N/A';
                    }
                    
                    // Update Allergies
                    const allergyContainer = document.getElementById('allergyTags');
                    const allergies = child.allergies ? child.allergies.split(',').map(a => a.trim()) : [];
                    if (allergies.length > 0) {
                        allergyContainer.innerHTML = allergies.map(allergy => `
                            <span class="allergy-tag">
                                <i class="fas fa-exclamation-triangle"></i> ${allergy}
                            </span>
                        `).join('');
                    } else {
                        allergyContainer.innerHTML = '<span class="no-data">None reported</span>';
                    }

                    // Update Medical notes
                    const conditionList = document.getElementById('conditionList');
                    if (child.medical_notes) {
                        conditionList.innerHTML = `
                            <div class="condition-item">
                                <i class="fas fa-notes-medical"></i>
                                <div>
                                    <h4>Medical History</h4>
                                    <p>${child.medical_notes}</p>
                                </div>
                            </div>
                        `;
                    } else {
                        conditionList.innerHTML = '<span class="no-data">No conditions reported</span>';
                    }

                    // Update Emergency Contact
                    const emergencyContainer = document.getElementById('emergencyContact');
                    emergencyContainer.querySelector('p').textContent = child.emergency_contact || 'Not provided';

                    // Update hidden inputs for modals
                    if (document.getElementById('vaccination_child_id')) {
                        document.getElementById('vaccination_child_id').value = child.id;
                    }
                    if (document.getElementById('medication_child_id')) {
                        document.getElementById('medication_child_id').value = child.id;
                    }
                    if (document.getElementById('health_record_child_id')) {
                        document.getElementById('health_record_child_id').value = child.id;
                    }

                    // Update Vaccination List
                    updateVaccinationList(child.vaccinations);

                    // Update Medication Table
                    updateMedicationTable(child.medications);

                    // Update Checkup History
                    updateCheckupHistory(child.checkups);
                }
            });
        });

        // Helper Functions for Dynamic Updates
        function updateVaccinationList(vaccinations) {
            const container = document.getElementById('vaccinationList');
            if (!vaccinations || vaccinations.length === 0) {
                container.innerHTML = `
                    <div class="empty-state" style="padding: 2rem; text-align: center;">
                        <i class="fas fa-syringe" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                        <p style="color: #6b7280;">No vaccination records found</p>
                    </div>`;
                return;
            }

            container.innerHTML = vaccinations.map(v => `
                <div class="vaccination-item ${v.status}">
                    <div class="vaccine-icon">
                        <i class="fas ${v.status === 'completed' ? 'fa-check-circle' : (v.status === 'upcoming' ? 'fa-clock' : 'fa-exclamation-triangle')}"></i>
                    </div>
                    <div class="vaccine-info">
                        <h4>${v.vaccine_name}</h4>
                        <p>${v.description || 'No description'}</p>
                        <span class="vaccine-date">
                            ${v.administered_date ? 'Administered: ' + new Date(v.administered_date).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'}) : 
                              (v.scheduled_date ? 'Scheduled: ' + new Date(v.scheduled_date).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'}) : '')}
                        </span>
                    </div>
                    <span class="vaccine-status ${v.status}">${v.status.charAt(0).toUpperCase() + v.status.slice(1)}</span>
                </div>
            `).join('');
        }

        function updateMedicationTable(medications) {
            const tbody = document.getElementById('medicationTableBody');
            if (!medications || medications.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <i class="fas fa-pills" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem; display: block;"></i>
                            <p style="color: #6b7280;">No medications found</p>
                        </td>
                    </tr>`;
                return;
            }

            tbody.innerHTML = medications.map(m => `
                <tr>
                    <td>
                        <div class="medication-name">
                            <i class="fas fa-capsules"></i>
                            <span>${m.medication_name}</span>
                        </div>
                    </td>
                    <td>${m.dosage}</td>
                    <td>${m.frequency}</td>
                    <td>${new Date(m.start_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
                    <td>${m.end_date ? new Date(m.end_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'Ongoing'}</td>
                    <td><span class="status-badge ${m.status}">${m.status.charAt(0).toUpperCase() + m.status.slice(1)}</span></td>
                    <td>
                        <button class="action-icon-btn" title="Edit" onclick="editMedication(${m.id})"><i class="fas fa-edit"></i></button>
                        <button class="action-icon-btn" title="Delete" onclick="deleteMedication(${m.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        function updateCheckupHistory(checkups, healthRecords) {
            const container = document.getElementById('checkupTimeline');
            
            // Combine and process records
            let allRecords = [];
            
            if (checkups && checkups.length > 0) {
                allRecords = allRecords.concat(checkups.map(c => ({...c, type: 'checkup', sort_date: c.checkup_date})));
            }
            
            if (healthRecords && healthRecords.length > 0) {
                allRecords = allRecords.concat(healthRecords.map(h => ({...h, type: 'health_record', sort_date: h.record_date})));
            }

            if (allRecords.length === 0) {
                container.innerHTML = `
                    <div class="empty-state" style="padding: 2rem; text-align: center;">
                        <i class="fas fa-notes-medical" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                        <p style="color: #6b7280;">No health records found</p>
                    </div>`;
                return;
            }

            // Sort by date desc
            const sortedRecords = allRecords.sort((a, b) => new Date(b.sort_date) - new Date(a.sort_date));

            container.innerHTML = sortedRecords.map(r => {
                const date = new Date(r.sort_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
                
                if (r.type === 'checkup') {
                    return `
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h4>${r.checkup_type}</h4>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <span class="timeline-date">${date}</span>
                                    <button class="action-icon-btn small" title="Edit" onclick="editCheckup(${r.id})">
                                        <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                    </button>
                                    <button class="action-icon-btn small" title="Delete" onclick="deleteCheckup(${r.id})">
                                        <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="timeline-doctor">${r.doctor_name || ''}${r.doctor_specialty ? ' - ' + r.doctor_specialty : ''}</p>
                            ${(r.weight || r.height || r.bmi) ? `
                            <div class="timeline-details">
                                ${r.weight ? `<div class="detail-item"><span class="detail-label">Weight:</span><span class="detail-value">${r.weight} kg</span></div>` : ''}
                                ${r.height ? `<div class="detail-item"><span class="detail-label">Height:</span><span class="detail-value">${r.height} cm</span></div>` : ''}
                                ${r.bmi ? `<div class="detail-item"><span class="detail-label">BMI:</span><span class="detail-value">${r.bmi}</span></div>` : ''}
                            </div>` : ''}
                            ${r.notes ? `<p class="timeline-notes"><strong>Notes:</strong> ${r.notes}</p>` : ''}
                        </div>
                    </div>`;
                } else {
                    // Health Record
                    return `
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h4>Health Log</h4>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <span class="timeline-date">${date}</span>
                                    <button class="action-icon-btn small" title="Edit" onclick="editHealthRecord(${r.id})">
                                        <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                    </button>
                                    <button class="action-icon-btn small" title="Delete" onclick="deleteHealthRecord(${r.id})">
                                        <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="timeline-details">
                                ${r.weight ? `<div class="detail-item"><span class="detail-label">Weight:</span><span class="detail-value">${r.weight} kg</span></div>` : ''}
                                ${r.height ? `<div class="detail-item"><span class="detail-label">Height:</span><span class="detail-value">${r.height} cm</span></div>` : ''}
                            </div>
                            ${r.notes ? `<p class="timeline-notes"><strong>Notes:</strong> ${r.notes}</p>` : ''}
                        </div>
                    </div>`;
                }
            }).join('');
        }

        // Modal Functions
        function openVaccinationModal() {
            if (childrenData.length === 0) {
                alert('Please add a child profile first.');
                return;
            }
            const modal = document.getElementById('vaccinationModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeVaccinationModal() {
            const modal = document.getElementById('vaccinationModal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function openMedicationModal() {
            if (childrenData.length === 0) {
                alert('Please add a child profile first.');
                return;
            }
            const modal = document.getElementById('medicationModal');
            const form = document.getElementById('medicationForm');
            const title = document.getElementById('medicationModalTitle');
            const submitBtn = document.getElementById('medicationSubmitBtn');
            const methodInput = document.getElementById('medication_method');
            const idInput = document.getElementById('medication_id');

            // Reset form for "Add" mode
            form.reset();
            title.textContent = 'Add Medication';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Medication';
            methodInput.value = 'POST';
            idInput.value = '';
            form.action = "{{ route('parent.health.medication.store') }}";
            
            // Ensure child_id is correct
            const activeTab = document.querySelector('.child-tab.active');
            const childId = activeTab ? activeTab.getAttribute('data-child') : (childrenData[0] ? childrenData[0].id : '');
            if(childId) document.getElementById('medication_child_id').value = childId;


            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeMedicationModal() {
            const modal = document.getElementById('medicationModal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function editMedication(medicationId) {
            const activeTab = document.querySelector('.child-tab.active');
            const childId = activeTab ? parseInt(activeTab.getAttribute('data-child')) : childrenData[0].id;
            const child = childrenData.find(c => c.id === childId);
            const medication = child.medications.find(m => m.id === medicationId);

            if (!medication) return;

            const modal = document.getElementById('medicationModal');
            const form = document.getElementById('medicationForm');
            const title = document.getElementById('medicationModalTitle');
            const submitBtn = document.getElementById('medicationSubmitBtn');
            const methodInput = document.getElementById('medication_method');
            const idInput = document.getElementById('medication_id');

            title.textContent = 'Edit Medication';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Medication';
            methodInput.value = 'PUT';
            idInput.value = medication.id;
            
            // Fix: route needs to be correct for update
            form.action = `/parent/health/medication/${medication.id}`;

            document.getElementById('medication_name').value = medication.medication_name;
            document.getElementById('dosage').value = medication.dosage;
            document.getElementById('frequency').value = medication.frequency;
            
            // Date formatting
            if (medication.start_date) {
               document.getElementById('start_date').value = medication.start_date.split('T')[0].split(' ')[0];
            }
            if (medication.end_date) {
               document.getElementById('end_date').value = medication.end_date.split('T')[0].split(' ')[0];
            } else {
               document.getElementById('end_date').value = '';
            }
            
            document.getElementById('medication_status').value = medication.status;
            document.getElementById('medication_notes').value = medication.notes || '';
            document.getElementById('medication_child_id').value = childId;

            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function deleteMedication(medicationId) {
            if (confirm('Are you sure you want to delete this medication record?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/parent/health/medication/${medicationId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }


        function openHealthRecordModal(mode = 'edit', recordId = null) {
            if (childrenData.length === 0) {
                alert('Please add a child profile first.');
                return;
            }

            const modal = document.getElementById('healthRecordModal');
            const form = document.getElementById('healthRecordForm');
            const modalTitle = document.getElementById('healthRecordModalTitle');
            const submitText = document.getElementById('healthRecordSubmitText');
            const methodInput = document.getElementById('health_record_method');
            const recordIdInput = document.getElementById('health_record_id');
            
            // Get active child
            const activeTab = document.querySelector('.child-tab.active');
            const childId = activeTab ? parseInt(activeTab.getAttribute('data-child')) : childrenData[0].id;
            const child = childrenData.find(c => c.id === childId);
            
            // Update child_id
            document.getElementById('health_record_child_id').value = childId;
            
            if (mode === 'edit' && child && child.health_records && child.health_records.length > 0) {
                let latestRecord;
                
                if (recordId) {
                    // Find specific record
                    latestRecord = child.health_records.find(r => r.id === recordId);
                    if (!latestRecord) return; // Record not found
                } else {
                     // Default to latest
                     latestRecord = child.health_records[0];
                }
                
                modalTitle.textContent = 'Edit Health Record';
                submitText.textContent = 'Update Record';
                methodInput.value = 'PUT';
                recordIdInput.value = latestRecord.id;
                
                // Update form action
                form.action = `/parent/health/record/${latestRecord.id}`;
                
                // Populate form fields
                document.getElementById('weight').value = latestRecord.weight || '';
                document.getElementById('height').value = latestRecord.height || '';
                
                if (latestRecord.record_date) {
                    let formattedDate = latestRecord.record_date;
                    if (formattedDate.includes('T')) {
                        formattedDate = formattedDate.split('T')[0];
                    } else if (formattedDate.includes(' ')) {
                         formattedDate = formattedDate.split(' ')[0];
                    }
                    document.getElementById('record_date').value = formattedDate;
                } else {
                    document.getElementById('record_date').value = '';
                }
                
                document.getElementById('health_notes').value = latestRecord.notes || '';
            } else {
                // Add mode
                form.reset();
                document.getElementById('health_record_child_id').value = childId;
                modalTitle.textContent = 'Add Health Record';
                submitText.textContent = 'Save Record';
                methodInput.value = 'POST';
                recordIdInput.value = '';
                form.action = '{{ route("parent.health.record.store") }}';
            }
            
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }
        
        function editHealthRecord(recordId) {
            openHealthRecordModal('edit', recordId);
        }
        
        function deleteHealthRecord(recordId) {
             if (confirm('Are you sure you want to delete this health record?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/parent/health/record/${recordId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Allergies Modal Functions
        function openAllergiesModal() {
            const modal = document.getElementById('allergiesModal');
            const childId = document.querySelector('.child-tab.active')?.dataset.childId || '{{ $children->first()->id ?? '' }}';
            
            // Update hidden child_id field
            document.getElementById('allergies_child_id').value = childId;
            
            // Find the selected child's data
            const selectedChild = childrenData.find(c => c.id == childId);
            
            if (selectedChild) {
                // Populate form with current child's data
                document.getElementById('allergies').value = selectedChild.allergies || '';
                document.getElementById('medical_notes').value = selectedChild.medical_notes || '';
                document.getElementById('emergency_contact').value = selectedChild.emergency_contact || '';
            }
            
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeAllergiesModal() {
            const modal = document.getElementById('allergiesModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function closeHealthRecordModal() {
            const modal = document.getElementById('healthRecordModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.getElementById('healthRecordForm').reset();
            }, 300);
        }

        // Checkup Modal Functions
        function openCheckupModal() {
            if (childrenData.length === 0) {
                alert('Please add a child profile first.');
                return;
            }
            const modal = document.getElementById('checkupModal');
            const form = document.getElementById('checkupForm');
            const title = document.getElementById('checkupModalTitle');
            const submitBtn = document.getElementById('checkupSubmitBtn');
            const methodInput = document.getElementById('checkup_method');
            const idInput = document.getElementById('checkup_id');

            // Reset form for "Add" mode
            form.reset();
            title.textContent = 'Add Checkup Record';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Record';
            methodInput.value = 'POST';
            idInput.value = '';
            form.action = "{{ route('parent.health.checkup.store') }}";
            
            // Ensure child_id is correct
            const activeTab = document.querySelector('.child-tab.active');
            const childId = activeTab ? activeTab.getAttribute('data-child') : (childrenData[0] ? childrenData[0].id : '');
            if(childId) document.getElementById('checkup_child_id').value = childId;

            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeCheckupModal() {
            const modal = document.getElementById('checkupModal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function editCheckup(checkupId) {
            const activeTab = document.querySelector('.child-tab.active');
            const childId = activeTab ? parseInt(activeTab.getAttribute('data-child')) : childrenData[0].id;
            const child = childrenData.find(c => c.id === childId);
            const checkup = child.checkups.find(c => c.id === checkupId);

            if (!checkup) return;

            const modal = document.getElementById('checkupModal');
            const form = document.getElementById('checkupForm');
            const title = document.getElementById('checkupModalTitle');
            const submitBtn = document.getElementById('checkupSubmitBtn');
            const methodInput = document.getElementById('checkup_method');
            const idInput = document.getElementById('checkup_id');

            title.textContent = 'Edit Checkup Record';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Record';
            methodInput.value = 'PUT';
            idInput.value = checkup.id;
            form.action = `/parent/health/checkup/${checkup.id}`;

            document.getElementById('checkup_type').value = checkup.checkup_type;
            
            if (checkup.checkup_date) {
               document.getElementById('checkup_date').value = checkup.checkup_date.split('T')[0].split(' ')[0];
            }
            
            document.getElementById('doctor_name').value = checkup.doctor_name || '';
            document.getElementById('doctor_specialty').value = checkup.doctor_specialty || '';
            document.getElementById('checkup_weight').value = checkup.weight || '';
            document.getElementById('checkup_height').value = checkup.height || '';
            document.getElementById('checkup_bmi').value = checkup.bmi || '';
            document.getElementById('checkup_notes').value = checkup.notes || '';
            document.getElementById('checkup_child_id').value = childId;

            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function deleteCheckup(checkupId) {
             if (confirm('Are you sure you want to delete this checkup record?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/parent/health/checkup/${checkupId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }


        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
                setTimeout(() => e.target.style.display = 'none', 300);
            }
        });


        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Fix date inputs to use client local time for max attribute
        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
            
            const checkupDate = document.getElementById('checkup_date');
            if (checkupDate) {
                checkupDate.max = today;
            }

            const recordDate = document.getElementById('record_date');
            if (recordDate) {
                recordDate.max = today;
            }
        });
    </script>
</body>
</html>
