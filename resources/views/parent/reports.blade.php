<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/reports.css'])
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
                    <a href="{{ route('parent.reports') }}" class="nav-item active">
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
                        <span>Notifications</span>
                        <span class="notification-dot" style="{{ $unreadCount > 0 ? 'display:block' : 'display:none' }}"></span>
                        <span class="badge">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <a href="{{ route('parent.health') }}" class="nav-item">
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
                    <h1>Progress Reports</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
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
                <div class="reports-container">
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-group">
                            <label>Select Child:</label>
                            <select class="filter-select" id="childFilter" onchange="filterReports()">
                                <option value="">All Children</option>
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                        {{ $child->first_name }} {{ $child->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Time Period:</label>
                            <select class="filter-select" id="periodFilter" onchange="filterReports()">
                                <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('period', 'this_month') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            </select>
                        </div>
                    </div>

                    <!-- Stats Overview -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ $daysPresent }} Days</h3>
                                <p>Present 
                                    @if($period == 'this_week')
                                        This Week
                                    @elseif($period == 'this_month')
                                        This Month
                                    @elseif($period == 'last_month')
                                        Last Month
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-shapes"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ $totalActivities }}</h3>
                                <p>Activities Completed</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ round($avgNapDuration) }} min</h3>
                                <p>Avg Nap Duration</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ $totalReports }}</h3>
                                <p>Daily Reports</p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Cards Grid -->
                    <div class="content-grid">
                        <!-- Progress Report Card (Latest Notes) -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-comment-dots"></i> Latest Teacher's Note</h2>
                            </div>
                            <div class="report-content">
                                <div class="teacher-notes">
                                    <p>{{ $latestTeacherNote }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Summary -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-clipboard-list"></i> Activity Summary (This Month)</h2>
                            </div>
                            <div class="activity-summary">
                                @forelse($topActivities as $activityName => $count)
                                    <div class="activity-stat">
                                        <div class="activity-icon art"> <!-- Generic icon/color class if names vary -->
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="activity-info">
                                            <h4>{{ ucfirst($activityName) }}</h4>
                                            <p>{{ $count }} sessions completed</p>
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-align: center; color: #94a3b8; padding: 20px;">
                                        No activities recorded this month.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports Table -->
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-file-alt"></i> Recent Reports</h2>
                            <button class="view-all-btn" onclick="updateFilters('view_all', '{{ request('view_all') ? '0' : '1' }}')">
                                {{ request('view_all') ? 'Show Less' : 'View All' }}
                            </button>
                        </div>
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Child</th>
                                    <th>Report Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReports as $report)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="child-name">
                                                <div class="child-avatar-small" style="background: linear-gradient(135deg, {{ $loop->iteration % 2 == 0 ? '#3b82f6, #2563eb' : '#10b981, #059669' }});">
                                                    {{ strtoupper(substr($report->child->first_name, 0, 1) . substr($report->child->last_name, 0, 1)) }}
                                                </div>
                                                <span>{{ $report->child->first_name }} {{ $report->child->last_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="report-type progress">Daily Report</span>
                                        </td>
                                        <td>
                                            @if($report->status == 'completed')
                                                <span class="status-badge completed">Completed</span>
                                            @elseif($report->status == 'draft')
                                                <span class="status-badge pending">Draft</span>
                                            @else
                                                <span class="status-badge" style="background: #e2e8f0; color: #475569;">{{ ucfirst($report->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="action-icon-btn view" title="View" 
                                                onclick="viewReport(
                                                    {{ $report->id }}, 
                                                    '{{ addslashes($report->child->first_name . ' ' . $report->child->last_name) }}', 
                                                    '{{ $report->report_date }}', 
                                                    '{{ $report->mood }}', 
                                                    {{ json_encode($report->meals) }}, 
                                                    '{{ $report->nap_duration }}', 
                                                    '{{ $report->nap_quality }}', 
                                                    {{ json_encode($report->activities) }}, 
                                                    '{{ addslashes($report->notes ?? '') }}',
                                                    {{ json_encode($report->medications_included) }},
                                                    '{{ addslashes($report->caregiver ? $report->caregiver->name : 'Unknown') }}',
                                                    '{{ addslashes($report->child->class) }}'
                                                )">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($report->status === 'completed')
                                                <a href="{{ route('parent.reports.download', $report->id) }}" class="action-icon-btn download" title="Download PDF">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <button class="action-icon-btn download disabled" title="Draft reports cannot be downloaded" disabled>
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #64748b; padding: 20px;">
                                            No reports found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Report Detail Modal -->
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
        // View report details in modal
        function viewReport(id, childName, date, mood, meals, napDuration, napQuality, activities, notes, medications, caregiverName, childClass) {
            // Set Header Title
            const formattedDate = new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            document.getElementById('modalTitle').textContent = `Daily Report - ${formattedDate}`;

            // Populate Summary
            document.getElementById('summaryChildName').textContent = childName;
            document.getElementById('summaryClass').textContent = childClass || 'N/A';
            document.getElementById('summaryCaregiver').textContent = caregiverName || 'Unknown';

            // Mood
            document.getElementById('modalMood').textContent = mood || 'Not recorded';
            
            // Display meals (List Format)
            let mealsHtml = '';
            if (meals && typeof meals === 'object') {
                for (let [meal, status] of Object.entries(meals)) {
                    mealsHtml += `<li><strong>${meal.charAt(0).toUpperCase() + meal.slice(1)}:</strong> ${status}</li>`;
                }
            }
            document.getElementById('modalMeals').innerHTML = mealsHtml || '<li style="color: #94a3b8">No meals recorded</li>';
            
            // Display nap
            let napText = '';
            if (napDuration) {
                napText = `${napDuration} minutes`;
                if (napQuality) napText += ` - ${napQuality}`;
            } else {
                napText = 'Not recorded';
            }
            document.getElementById('modalNap').textContent = napText;
            
            // Display activities (Tags)
            let activitiesHtml = '';
            if (activities && Array.isArray(activities) && activities.length > 0) {
                activities.forEach(activity => {
                    // Check if activity is an object (new structure) or string (old structure)
                    let activityName = typeof activity === 'object' ? (activity.name || 'Unknown Activity') : activity;
                    activitiesHtml += `<span class="activity-tag">${activityName}</span>`;
                });
            } else {
                activitiesHtml = '<span style="color: #94a3b8; font-size: 13px;">No activities recorded</span>';
            }
            document.getElementById('modalActivities').innerHTML = activitiesHtml;
            
            // Display medications (Detailed List)
            let medicationsHtml = '';
            if (medications && Array.isArray(medications) && medications.length > 0) {
                medications.forEach(med => {
                    let medObj = med;
                    // If it's a string that looks like JSON, try to parse it
                    if (typeof med === 'string' && (med.startsWith('{') || med.startsWith('['))) {
                        try {
                            medObj = JSON.parse(med);
                        } catch (e) {
                            medObj = med; // Keep as string if parse fails
                        }
                    }

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
            
            document.getElementById('modalNotes').textContent = notes || 'No additional notes.';
            
            // Show modal
            document.getElementById('reportModal').style.display = 'flex';
        }
        
        function closeReportModal() {
            document.getElementById('reportModal').style.display = 'none';
        }

        // Filter update function to preserve both child_id and period
        function updateFilters(paramName, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(paramName, value);
            window.location.href = url.toString();
        }

        // Child tab switching
        document.querySelectorAll('.child-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.child-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                // Here you would load the specific child's data
            });
        });

        // Filter reports
        function filterReports() {
            const childId = document.getElementById('childFilter').value;
            const period = document.getElementById('periodFilter').value;
            
            let url = '{{ route("parent.reports") }}?';
            if (childId) url += 'child_id=' + childId + '&';
            if (period) url += 'period=' + period;
            
            window.location.href = url;
        }

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
