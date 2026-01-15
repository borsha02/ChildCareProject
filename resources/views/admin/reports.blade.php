<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Reports - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/reports.css'])
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-body {
            padding: 20px;
        }
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
        }
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .detail-row {
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
        }
        .detail-label {
            font-weight: 600;
            min-width: 120px;
        }
        .badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-present { background: #dcfce7; color: #166534; }
        .badge-absent { background: #fee2e2; color: #991b1b; }
        .badge-late { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <h2>Admin Panel</h2>
                </div>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div class="user-details">
                        <h4>Administrator</h4>
                        <p>System Admin</p>
                    </div>
                </div>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="{{ route('admin.users') }}" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="{{ route('admin.children') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Records</span>
                    </a>
                    <a href="{{ route('admin.staff') }}" class="nav-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Staff Management</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Operations</div>
                    <a href="{{ route('admin.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-item active">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('admin.invoices') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('admin.payments.pending') }}" class="nav-item">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Approvals</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>

                    <a href="{{ route('admin.communication') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Communication Logs</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
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
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Daily Activity Reports</h1>
                </div>
                <div class="top-bar-actions">
                    <input type="date" id="report_date" class="date-picker" value="{{ $date }}" onchange="window.location.href='{{ route('admin.reports') }}?date=' + this.value">
                    <button class="export-btn" onclick="window.open('{{ route('admin.reports.export.pdf') }}?date={{ $date }}', '_blank')">
                        <i class="fas fa-download"></i>
                        Export Reports
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <select class="filter-select" id="childFilter" onchange="filterReports()">
                        <option value="">All Children</option>
                        @foreach($children as $child)
                            <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }}</option>
                        @endforeach
                    </select>
                    <select class="filter-select" id="typeFilter" onchange="filterReports()">
                        <option value="">All Types</option>
                        <option value="meal">Meals</option>
                        <option value="nap">Naps</option>
                        <option value="activity">Activities</option>
                    </select>
                    <select class="filter-select" id="packageFilter" onchange="filterReports()">
                        <option value="">All Packages</option>
                        <option value="weekly">Weekly Students</option>
                        <option value="monthly">Monthly Students</option>
                    </select>
                </div>

                <!-- Reports Grid -->
                <div class="reports-grid" id="reportsGrid">
                    @forelse($dailyReports as $report)
                        @php
                            $childPackage = $report->child->package ?? '';
                        @endphp

                        {{-- Meals Card --}}
                        @if($report->meals)
                            <div class="report-card meal" data-type="meal" data-child-id="{{ $report->child_id }}" data-package="{{ strtolower($childPackage) }}">
                                <div class="report-header">
                                    <div class="report-title">
                                        <h3>Meals - {{ $report->child->first_name }}</h3>
                                        <div class="report-meta">{{ \Carbon\Carbon::parse($report->created_at)->format('g:i A') }}</div>
                                    </div>
                                    <div class="report-icon meal"><i class="fas fa-utensils"></i></div>
                                </div>
                                <div class="report-content">
                                    <div class="report-item">
                                        <span class="report-label">Meals</span>
                                        <span class="report-value">
                                            @foreach($report->meals as $meal)
                                                {{ ucfirst($meal) }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                                <div class="report-footer">
                                    <span class="reporter-info">Reported by: {{ $report->caregiver->name }}</span>
                                    <button class="view-btn" onclick="openModal('{{ $report->id }}', 'meal')">View Details</button>
                                </div>
                            </div>
                        @endif

                        {{-- Nap Card --}}
                        @if($report->nap_duration)
                            <div class="report-card nap" data-type="nap" data-child-id="{{ $report->child_id }}" data-package="{{ strtolower($childPackage) }}">
                                <div class="report-header">
                                    <div class="report-title">
                                        <h3>Nap - {{ $report->child->first_name }}</h3>
                                        <div class="report-meta">{{ \Carbon\Carbon::parse($report->created_at)->format('g:i A') }}</div>
                                    </div>
                                    <div class="report-icon nap"><i class="fas fa-bed"></i></div>
                                </div>
                                <div class="report-content">
                                    <div class="report-item">
                                        <span class="report-label">Duration</span>
                                        <span class="report-value">{{ $report->nap_duration }} mins</span>
                                    </div>
                                    <div class="report-item">
                                        <span class="report-label">Quality</span>
                                        <span class="report-value">{{ ucfirst($report->nap_quality) }}</span>
                                    </div>
                                </div>
                                <div class="report-footer">
                                    <span class="reporter-info">Reported by: {{ $report->caregiver->name }}</span>
                                    <button class="view-btn" onclick="openModal('{{ $report->id }}', 'nap')">View Details</button>
                                </div>
                            </div>
                        @endif

                         {{-- Activity Card --}}
                         @if($report->activities)
                            <div class="report-card activity" data-type="activity" data-child-id="{{ $report->child_id }}" data-package="{{ strtolower($childPackage) }}">
                                <div class="report-header">
                                    <div class="report-title">
                                        <h3>Activity - {{ $report->child->first_name }}</h3>
                                        <div class="report-meta">{{ \Carbon\Carbon::parse($report->created_at)->format('g:i A') }}</div>
                                    </div>
                                    <div class="report-icon activity"><i class="fas fa-palette"></i></div>
                                </div>
                                <div class="report-content">
                                    <div class="report-item">
                                        <span class="report-label">Activities</span>
                                        <span class="report-value">
                                             @foreach($report->activities as $activity)
                                                {{ ucfirst($activity) }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </span>
                                    </div>
                                    <div class="report-item">
                                        <span class="report-label">Mood</span>
                                        <span class="report-value">{{ ucfirst($report->mood) }}</span>
                                    </div>
                                </div>
                                <div class="report-footer">
                                    <span class="reporter-info">Reported by: {{ $report->caregiver->name }}</span>
                                    <button class="view-btn" onclick="openModal('{{ $report->id }}', 'activity')">View Details</button>
                                </div>
                            </div>
                        @endif

                    @empty
                        <div class="no-data" style="width: 100%; grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                            <h3>No reports found for this date.</h3>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- Report Detail Modal (Smart Design) -->
    <div id="reportModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; align-items: center; justify-content: center;">
        <div class="modal-content-smart">
            <div class="modal-header-smart">
                <h2 id="modalTitle">Daily Report</h2>
                <button class="modal-close-btn" onclick="closeReportModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- Summary Section -->
                <div class="modal-summary">
                    <div class="summary-row">
                        <span class="summary-label">Child Name:</span>
                        <span id="summaryChildName" class="summary-value"></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Class:</span>
                        <span id="summaryClass" class="summary-value"></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Caregiver:</span>
                        <span id="summaryCaregiver" class="summary-value"></span>
                    </div>
                </div>

                <div class="smart-grid">
                    <!-- Mood Card -->
                    <div class="info-card">
                        <span class="card-label">Mood</span>
                        <div id="modalMood" class="card-content"></div>
                    </div>

                    <!-- Meals Card -->
                    <div class="info-card">
                        <span class="card-label">Meals</span>
                        <ul id="modalMeals" class="meal-list"></ul>
                    </div>

                    <!-- Nap Card -->
                    <div class="info-card">
                        <span class="card-label">Nap Time</span>
                        <div id="modalNap" class="card-content"></div>
                    </div>

                    <!-- Activities Card -->
                    <div class="info-card">
                        <span class="card-label">Activities</span>
                        <div id="modalActivities" class="activity-tags"></div>
                    </div>

                    <!-- Medications Card (Full Width) -->
                    <div class="info-card full-width">
                        <span class="card-label">Medications Administered</span>
                        <div id="modalMedications" class="medication-list"></div>
                    </div>

                    <!-- Notes Card (Full Width) -->
                    <div class="info-card full-width">
                        <span class="card-label">Notes & Observations</span>
                        <p id="modalNotes" class="card-content notes-text"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Reports Data
        const reportsData = @json($dailyReports);

        function filterReports() {
            const childId = document.getElementById('childFilter').value;
            const type = document.getElementById('typeFilter').value;
            const package = document.getElementById('packageFilter').value.toLowerCase(); // weekly or monthly

            const cards = document.querySelectorAll('.report-card');
            
            cards.forEach(card => {
                const cardChildId = card.dataset.childId;
                const cardType = card.dataset.type;
                const cardPackage = card.dataset.package;

                let show = true;

                if (childId && cardChildId !== childId) show = false;
                if (type && cardType !== type) show = false;
                if (package && cardPackage !== package) show = false;

                card.style.display = show ? '' : 'none';
            });
        }

        function openModal(reportId, type) {
            const report = reportsData.find(r => r.id == reportId);
            if (!report) return;

            // Set Header Title
            const formattedDate = new Date(report.report_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            document.getElementById('modalTitle').textContent = `Daily Report - ${formattedDate}`;

            // Populate Summary
            document.getElementById('summaryChildName').textContent = `${report.child.first_name} ${report.child.last_name}`;
            document.getElementById('summaryClass').textContent = report.child.class || 'N/A';
            document.getElementById('summaryCaregiver').textContent = report.caregiver ? report.caregiver.name : 'Unknown';

            // Mood
            document.getElementById('modalMood').textContent = report.mood ? ucFirst(report.mood) : 'Not recorded';
            
            // Display meals (List Format)
            let mealsHtml = '';
            // Safe parse if string, though usually array in backend casts
            let meals = report.meals;
            if (typeof meals === 'string') {
                 try { meals = JSON.parse(meals); } catch(e) {}
            }

            if (meals && typeof meals === 'object') {
                // Determine if array or object
                if (Array.isArray(meals)) {
                    meals.forEach(m => mealsHtml += `<li><strong>Item:</strong> ${m}</li>`);
                } else {
                     for (let [meal, status] of Object.entries(meals)) {
                        mealsHtml += `<li><strong>${ucFirst(meal)}:</strong> ${status}</li>`;
                    }
                }
            }
            document.getElementById('modalMeals').innerHTML = mealsHtml || '<li style="color: #94a3b8">No meals recorded</li>';
            
            // Display nap
            let napText = '';
            if (report.nap_duration) {
                napText = `${report.nap_duration} minutes`;
                if (report.nap_quality) napText += ` - ${ucFirst(report.nap_quality)}`;
            } else {
                napText = 'Not recorded';
            }
            document.getElementById('modalNap').textContent = napText;
            
            // Display activities (Tags)
            let activitiesHtml = '';
            let activities = report.activities;
            if (typeof activities === 'string') {
                 try { activities = JSON.parse(activities); } catch(e) {}
            }

            if (activities && Array.isArray(activities) && activities.length > 0) {
                activities.forEach(activity => {
                    let activityName = typeof activity === 'object' ? (activity.name || 'Unknown Activity') : activity;
                    activitiesHtml += `<span class="activity-tag">${activityName}</span>`;
                });
            } else {
                activitiesHtml = '<span style="color: #94a3b8; font-size: 13px;">No activities recorded</span>';
            }
            document.getElementById('modalActivities').innerHTML = activitiesHtml;
            
            // Display medications (Detailed List)
            let medicationsHtml = '';
            let medications = report.medications_included; // Using the key from the report object
            // If the controller doesn't send medications_included but sends medications
            if (!medications && report.medications) medications = report.medications;

             if (typeof medications === 'string') {
                 try { medications = JSON.parse(medications); } catch(e) {}
            }

            if (medications && Array.isArray(medications) && medications.length > 0) {
                medications.forEach(med => {
                    let medObj = med;
                    // Extract name and details
                    let name = 'Unknown Medication';
                    let time = '';
                    let doseInfo = '';
                    
                    if (typeof medObj === 'object' && medObj !== null) {
                        name = medObj.medication_name || medObj.name || 'Unknown Medication';
                        time = medObj.time ? `Given at ${medObj.time}` : 'Time not recorded';
                        
                        // Build dose information
                        let doseParts = [];
                        if (medObj.dose_index) {
                            doseParts.push(`Dose ${medObj.dose_index}`);
                        }
                        if (medObj.amount) {
                            doseParts.push(medObj.amount);
                        }
                        if (doseParts.length > 0) {
                            doseInfo = ` (${doseParts.join(' - ')})`;
                        }
                    } else {
                        name = String(medObj);
                    }

                    medicationsHtml += `
                        <div class="medication-item">
                            <span class="med-name">${name}${doseInfo}</span>
                            <span class="med-time">
                                <i class="fas fa-check-circle"></i> ${time}
                            </span>
                        </div>
                    `;
                });
            } else {
                 medicationsHtml = '<p style="color: #94a3b8; font-size: 14px;">No medications administered</p>';
            }
            document.getElementById('modalMedications').innerHTML = medicationsHtml;
            
            document.getElementById('modalNotes').textContent = report.notes || 'No additional notes.';
            
            // Show modal
            document.getElementById('reportModal').style.display = 'flex';
        }
        
        function closeReportModal() {
            document.getElementById('reportModal').style.display = 'none';
        }

        // Helper for capitalization
        function ucFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Close on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('reportModal');
            if (event.target == modal) {
                closeReportModal();
            }
        }

        // Mobile toggle
         document.addEventListener('click', (e) => {
             const sidebar = document.getElementById('sidebar');
             const toggle = document.querySelector('.mobile-toggle');
             if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                 sidebar.classList.remove('active');
             }
         });

         // Ensure correct date based on client timezone
         document.addEventListener('DOMContentLoaded', () => {
             const dateInput = document.getElementById('report_date');
             if (!dateInput) return;
 
             const urlParams = new URLSearchParams(window.location.search);
             const hasDateParam = urlParams.has('date');
 
             if (!hasDateParam) {
                 const serverDate = dateInput.value;
                 const clientDate = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
 
                 if (serverDate !== clientDate) {
                     // Redirect to client date to load correct data
                     window.location.search = `?date=${clientDate}`;
                 }
             }
         });
    </script>
</body>
</html>
