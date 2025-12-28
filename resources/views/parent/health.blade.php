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
                    <h2>Childcare</h2>
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
                        <span class="badge">3</span>
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">5</span>
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
                    <div class="search-box">
                        <input type="text" placeholder="Search records...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
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
                                <button class="add-btn">
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
                                            <button class="action-icon-btn" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-icon-btn" title="Delete">
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
                            <button class="view-all-btn">View All</button>
                        </div>
                        <div class="checkup-timeline" id="checkupTimeline">
                            @if($children->isNotEmpty() && $children->first()->checkups->isNotEmpty())
                                @foreach($children->first()->checkups->sortByDesc('checkup_date') as $checkup)
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <h4>{{ $checkup->checkup_type }}</h4>
                                            <span class="timeline-date">{{ \Carbon\Carbon::parse($checkup->checkup_date)->format('M j, Y') }}</span>
                                        </div>
                                        <p class="timeline-doctor">{{ $checkup->doctor_name }}{{ $checkup->doctor_specialty ? ' - ' . $checkup->doctor_specialty : '' }}</p>
                                        @if($checkup->weight || $checkup->height || $checkup->bmi)
                                        <div class="timeline-details">
                                            @if($checkup->weight)
                                            <div class="detail-item">
                                                <span class="detail-label">Weight:</span>
                                                <span class="detail-value">{{ $checkup->weight }} kg</span>
                                            </div>
                                            @endif
                                            @if($checkup->height)
                                            <div class="detail-item">
                                                <span class="detail-label">Height:</span>
                                                <span class="detail-value">{{ $checkup->height }} cm</span>
                                            </div>
                                            @endif
                                            @if($checkup->bmi)
                                            <div class="detail-item">
                                                <span class="detail-label">BMI:</span>
                                                <span class="detail-value">{{ $checkup->bmi }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                        @if($checkup->notes)
                                        <p class="timeline-notes">
                                            <strong>Notes:</strong> {{ $checkup->notes }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="empty-state" style="padding: 2rem; text-align: center;">
                                    <i class="fas fa-notes-medical" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                    <p style="color: #6b7280;">No checkup records found</p>
                                </div>
                            @endif
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
                        <label for="vaccine_name">Vaccine Name *</label>
                        <input type="text" id="vaccine_name" name="vaccine_name" required placeholder="e.g., MMR Vaccine">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="e.g., Measles, Mumps, Rubella"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status *</label>
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
                <h2>Add Medication</h2>
                <button class="close-modal" onclick="closeMedicationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('parent.health.medication.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="medication_child_id" value="{{ $children->first()->id ?? '' }}">
                    
                    <div class="form-group">
                        <label for="medication_name">Medication Name *</label>
                        <input type="text" id="medication_name" name="medication_name" required placeholder="e.g., Albuterol Inhaler">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dosage">Dosage *</label>
                            <input type="text" id="dosage" name="dosage" required placeholder="e.g., 2 puffs">
                        </div>
                        <div class="form-group">
                            <label for="frequency">Frequency *</label>
                            <input type="text" id="frequency" name="frequency" required placeholder="e.g., As needed">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="medication_status">Status *</label>
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
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Medication
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
                            <label for="weight">Weight (kg) *</label>
                            <input type="number" id="weight" name="weight" step="0.01" min="0" max="999.99" required placeholder="e.g., 18.5">
                        </div>
                        <div class="form-group">
                            <label for="height">Height (cm) *</label>
                            <input type="number" id="height" name="height" step="0.01" min="0" max="999.99" required placeholder="e.g., 110">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="record_date">Record Date *</label>
                        <input type="date" id="record_date" name="record_date" required max="{{ date('Y-m-d') }}">
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
                }
            });
        });

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
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeMedicationModal() {
            const modal = document.getElementById('medicationModal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }


        function openHealthRecordModal(mode = 'edit') {
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
                // Edit mode - populate with latest health record
                const latestRecord = child.health_records[0];
                
                console.log('Latest health record:', latestRecord); // Debug log
                
                modalTitle.textContent = 'Edit Health Record';
                submitText.textContent = 'Update Record';
                methodInput.value = 'PUT';
                recordIdInput.value = latestRecord.id;
                
                // Update form action
                form.action = `/parent/health/record/${latestRecord.id}`;
                
                // Populate form fields WITHOUT resetting first
                document.getElementById('weight').value = latestRecord.weight || '';
                document.getElementById('height').value = latestRecord.height || '';
                
                // Format date properly for HTML date input (YYYY-MM-DD)
                if (latestRecord.record_date) {
                    console.log('Record date value:', latestRecord.record_date); // Debug log
                    // Simply assign the date - Laravel should return it in YYYY-MM-DD format
                    document.getElementById('record_date').value = latestRecord.record_date;
                } else {
                    document.getElementById('record_date').value = '';
                }
                
                document.getElementById('health_notes').value = latestRecord.notes || '';
            } else {
                // Add mode - reset form first
                form.reset();
                document.getElementById('health_record_child_id').value = childId;
                // Add mode
                modalTitle.textContent = 'Add Health Record';
                submitText.textContent = 'Save Record';
                methodInput.value = 'POST';
                recordIdInput.value = '';
                form.action = '{{ route("parent.health.record.store") }}';
            }
            
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeHealthRecordModal() {
            const modal = document.getElementById('healthRecordModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.getElementById('healthRecordForm').reset();
            }, 300);
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
    </script>
</body>
</html>
