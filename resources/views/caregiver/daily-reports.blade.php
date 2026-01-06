@extends('layouts.caregiver')

@section('title', 'Daily Reports')

@section('styles')
    @vite(['resources/css/caregiver/daily-reports.css'])
@endsection

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <div style="display: flex; align-items: center;">
            <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Daily Reports</h1>
        </div>
        <div class="top-bar-actions">
            <button onclick="document.getElementById('create-report-form').scrollIntoView({behavior: 'smooth'})"
                class="btn-create">
                <i class="fas fa-plus"></i> Create New Report
            </button>
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="notification-dot"></span>
            </a>
            <a href="{{ route('caregiver.messages') }}"
                class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->count() }}</h3>
                    <p>Reports Today</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <!-- Logic for pending reports: assigned children count - today's reports count -->
                <div class="stat-details">
                    <h3>{{ $pendingReportsCount }}</h3>
                    <p>Pending Reports</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $completedWeekCount }}</h3>
                    <p>Completed This Week</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $completionRate }}%</h3>
                    <p>Completion Rate</p>
                </div>
            </div>
        </div>

        <!-- Create Report Form -->
        <div class="card create-report-section" id="create-report-form">
            <div class="card-header">
                <h3>Create Daily Report</h3>
            </div>
            <form action="{{ route('caregiver.reports.store') }}" method="POST" class="form-grid">
                @csrf
                <div class="form-row">
                    <div>
                        <label class="form-label">Select Child</label>
                        <select name="child_id" required class="form-control">
                            <option value="">Select a child</option>
                            @foreach($assignedChildren as $child)
                                <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Date</label>
                        <input type="date" name="report_date" id="report_date" class="form-control">
                    </div>
                </div>

                <div>
                    <label class="form-label">Mood</label>
                    <input type="hidden" name="mood" id="selectedMood">
                    <div class="mood-buttons">
                        <button type="button" onclick="selectMood('Happy', this)" class="mood-btn">😄</button>
                        <button type="button" onclick="selectMood('Content', this)" class="mood-btn">😊</button>
                        <button type="button" onclick="selectMood('Fussy', this)" class="mood-btn">😐</button>
                        <button type="button" onclick="selectMood('Sad', this)" class="mood-btn">😢</button>
                    </div>
                </div>

                <div>
                    <label class="form-label">Meals</label>
                    <div class="meals-grid">
                        <div>
                            <label class="sub-label">Breakfast</label>
                            <select name="meals[breakfast]" class="meal-select">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="sub-label">Lunch</label>
                            <select name="meals[lunch]" class="meal-select">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="sub-label">Snack</label>
                            <select name="meals[snack]" class="meal-select">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label">Nap Time</label>
                    <div class="nap-grid">
                        <div>
                            <label class="sub-label">Duration (minutes)</label>
                            <input type="number" name="nap_duration" placeholder="90" class="meal-select">
                        </div>
                        <div>
                            <label class="sub-label">Quality</label>
                            <select name="nap_quality" class="meal-select">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label">Activities Participated</label>
                    <div class="activities-container">
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Art & Crafts"> Art & Crafts
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Outdoor Play"> Outdoor Play
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Music & Dance"> Music & Dance
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Story Time"> Story Time
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Science"> Science
                        </label>
                    </div>
                </div>

                <div>
                    <label class="form-label">Medications Administered</label>
                    <div id="medications-container" class="medications-list">
                        <div class="empty-state-small">Select a child to view active medications</div>
                    </div>
                </div>

                <div>
                    <label class="form-label">Notes & Observations</label>
                    <textarea name="notes" rows="4" placeholder="Enter any observations, achievements, or concerns..." class="notes-area"></textarea>
                </div>

                <div class="form-actions" style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="submit" name="submit_action" value="save_draft" class="btn-submit" style="background-color: #6b7280; color: white;">
                        Save Draft
                    </button>
                    <button type="submit" name="submit_action" value="complete" class="btn-submit">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Reports -->
        <div class="card">
            <div class="card-header">
                <h3>Recent Reports</h3>
            </div>
            <div class="reports-list">
                @forelse($recentReports as $report)
                    <div class="report-item">
                        <div class="report-header">
                            <div>
                                <h4 class="report-title">{{ $report->child->first_name }} {{ $report->child->last_name }} - {{ $report->report_date->format('F d, Y') }}</h4>
                                <p class="report-meta">
                                    Mood: {{ $report->mood }} • 
                                    Meals: B:{{ $report->meals['breakfast'] ?? '-' }}/L:{{ $report->meals['lunch'] ?? '-' }}/S:{{ $report->meals['snack'] ?? '-' }} • 
                                    Nap: {{ $report->nap_duration }} mins ({{ $report->nap_quality }})
                                </p>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button onclick="viewReport({{ $report->id }})" class="btn-view-report" title="View Full Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <span class="status-badge">Submitted</span>
                            </div>
                        </div>
                        <p class="report-notes">{{ Str::limit($report->notes, 100) }}</p>
                        @if(!empty($report->activities))
                            <div class="activity-tags">
                                @foreach($report->activities as $activity)
                                    <span class="activity-tag">{{ $activity }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-reports">No reports filed recently.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- View Report Modal -->
    <div class="modal" id="viewReportModal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2 id="modalReportTitle">Report Details</h2>
                <button class="close-modal" onclick="closeReportModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalReportBody">
                <!-- Report details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Set default date to client's local today
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('report_date');
            if (dateInput && !dateInput.value) {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                dateInput.value = `${year}-${month}-${day}`;
            }
        });

        const reportsData = @json($recentReports);
        const childrenData = @json($assignedChildren);

        // Shared function to update medications
        function updateMedicationList(existingMeds = []) {
            const childSelect = document.querySelector('select[name="child_id"]');
            const dateInput = document.getElementById('report_date');
            const container = document.getElementById('medications-container');
            
            const childId = childSelect.value;
            const reportDate = dateInput.value;
            
            container.innerHTML = ''; // Clear previous

            if (!childId) {
                container.innerHTML = '<div class="empty-state-small">Select a child to view active medications</div>';
                return;
            }

            if (!reportDate) {
                container.innerHTML = '<div class="empty-state-small">Select a date to view active medications</div>';
                return;
            }

            const selectedChild = childrenData.find(c => c.id == childId);
            const allMedications = selectedChild ? selectedChild.medications : [];

            // Filter medications based on Report Date
            const medications = allMedications.filter(med => {
                const medStart = new Date(med.start_date);
                const medEnd = med.end_date ? new Date(med.end_date) : null;
                const rDate = new Date(reportDate);
                
                // Reset times to compare dates only
                medStart.setHours(0,0,0,0);
                if(medEnd) medEnd.setHours(0,0,0,0);
                rDate.setHours(0,0,0,0);

                const isActive = rDate >= medStart && (!medEnd || rDate <= medEnd);
                return isActive; // Only show if active on the report date
            });

            if (medications.length === 0) {
                container.innerHTML = '<div class="empty-state-small">No active medications for this date.</div>';
                
                const noneInput = document.createElement('input');
                noneInput.type = 'hidden';
                noneInput.name = 'medication_log'; 
                noneInput.value = 'none';
                container.appendChild(noneInput);
                return;
            }

            let logIndex = 0;
            
            medications.forEach(med => {
                // Parse frequency to get count (e.g. "2 times" -> 2)
                let count = parseInt(med.frequency);
                if (isNaN(count) || count < 1) count = 1;
                if (count > 10) count = 10; // Safety limit

                // Create a container for this medication's doses
                const medGroup = document.createElement('div');
                medGroup.className = 'medication-group';
                
                medGroup.innerHTML = `
                    <div class="medication-group-header">
                        <span>${med.medication_name}</span>
                        <span class="medication-meta">${med.dosage} • ${med.frequency}</span>
                    </div>
                `;

                for(let i = 0; i < count; i++) {
                    const item = document.createElement('div');
                    item.className = 'medication-item';
                    
                    const uniqueId = `med_${med.id}_${i}`;
                    const labelText = count > 1 ? `Dose ${i + 1}` : 'Mark as Given';

                    // Check if this specific dose was already given in the draft
                    // existingMeds structure: array of objects {medication_id, dose_index, given, time, ...}
                    // match by medication_id AND dose_index (or infer index if not strict)
                    // Since we save full log, we should find exact match
                    let foundDose = null;
                    if (existingMeds && existingMeds.length > 0) {
                         foundDose = existingMeds.find(m => 
                            m.medication_id == med.id && 
                            // Legacy support (check index only if present, or infer from list order?)
                            // Better to match by dose_index if we saved it. We did add it to hidden field.
                            (m.dose_index == (i + 1)) 
                        );
                    }

                    const isChecked = foundDose && (foundDose.given == "1" || foundDose.status == "Given");
                    const savedTime = foundDose ? foundDose.time : '';

                    item.innerHTML = `
                        <div class="med-action">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="${uniqueId}" name="medication_log[${logIndex}][given]" value="1" onchange="toggleMedicationState(this)" ${isChecked ? 'checked' : ''}>
                                <label for="${uniqueId}">${labelText}</label>
                                <input type="hidden" name="medication_log[${logIndex}][medication_id]" value="${med.id}">
                                <input type="hidden" name="medication_log[${logIndex}][medication_name]" value="${med.medication_name}">
                                <input type="hidden" name="medication_log[${logIndex}][dose_index]" value="${i + 1}">
                            </div>
                            <div class="time-input-wrapper" style="${isChecked ? 'display: block;' : 'display: none;'}">
                                <input type="time" name="medication_log[${logIndex}][time]" value="${savedTime}" placeholder="Time" ${isChecked ? '' : 'disabled'}>
                            </div>
                        </div>
                    `;
                    
                    // Add checked class if needed right away
                    if(isChecked) item.classList.add('checked');

                    medGroup.appendChild(item);
                    logIndex++;
                }
                container.appendChild(medGroup);
            });
        }

        function toggleMedicationState(checkbox) {
            // Find parent item to toggle style class
            const medItem = checkbox.closest('.medication-item');
            const wrapper = medItem.querySelector('.time-input-wrapper');
            const timeInput = wrapper.querySelector('input');
            
            if (checkbox.checked) {
                medItem.classList.add('checked');
                wrapper.style.display = 'block';
                timeInput.disabled = false;
                timeInput.required = true;
                
                // Set default time to now if empty
                if (!timeInput.value) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    timeInput.value = `${hours}:${minutes}`;
                }
            } else {
                medItem.classList.remove('checked');
                wrapper.style.display = 'none';
                timeInput.disabled = true;
                timeInput.required = false;
                timeInput.value = '';
            }
        }
        
        async function checkDraft() {
            const childId = document.querySelector('select[name="child_id"]').value;
            const reportDate = document.getElementById('report_date').value;

            if (!childId || !reportDate) return;

            try {
                const response = await fetch(`{{ route('caregiver.daily-reports.check') }}?child_id=${childId}&date=${reportDate}`);
                const data = await response.json();
                populateForm(data);
            } catch (error) {
                console.error('Error checking draft:', error);
                // Even on error, update meds list (empty)
                updateMedicationList([]);
            }
        }

        function populateForm(data) {
            // Reset common fields first
            document.querySelector('textarea[name="notes"]').value = '';
            // Reset mood buttons
            document.querySelectorAll('.mood-btn').forEach(b => {
                b.style.borderColor = '#e5e7eb';
                b.style.background = 'white';
            });
            document.getElementById('selectedMood').value = '';
            // Reset selects
            document.querySelectorAll('select').forEach(s => {
                if(s.name !== 'child_id') s.value = ''; // Don't reset child select
            });
            // Reset inputs
            document.querySelector('input[name="nap_duration"]').value = '';

            if (!data) {
                // No draft, just show empty meds list
                updateMedicationList([]);
                return;
            }

            // Populate fields
            if (data.notes) document.querySelector('textarea[name="notes"]').value = data.notes;
            if (data.nap_duration) document.querySelector('input[name="nap_duration"]').value = data.nap_duration;
            if (data.nap_quality) document.querySelector('select[name="nap_quality"]').value = data.nap_quality;
            
            // Meals
            if (data.meals) {
                if (data.meals.breakfast) document.querySelector('select[name="meals[breakfast]"]').value = data.meals.breakfast;
                if (data.meals.lunch) document.querySelector('select[name="meals[lunch]"]').value = data.meals.lunch;
                if (data.meals.snack) document.querySelector('select[name="meals[snack]"]').value = data.meals.snack;
            }

            // Mood
            if (data.mood) {
                const btn = Array.from(document.querySelectorAll('.mood-btn')).find(b => b.textContent.trim().includes(getMoodEmoji(data.mood)) || b.getAttribute('onclick').includes(data.mood));
                // Since onclick has 'Happy', we can match intent. My helper logic below is simpler:
                // Just use the existing helper function logic manually
                 const moodMap = { 'Happy': '😄', 'Content': '😊', 'Fussy': '😐', 'Sad': '😢' };
                 // Find button by onclick text
                 const targetBtn = document.querySelector(`button[onclick*="'${data.mood}'"]`);
                 if(targetBtn) selectMood(data.mood, targetBtn);
            }

            // Activities
            // Reset all checkboxes first
            document.querySelectorAll('input[name="activities[]"]').forEach(cb => cb.checked = false);
            if (data.activities && Array.isArray(data.activities)) {
                data.activities.forEach(activity => {
                    const cb = document.querySelector(`input[name="activities[]"][value="${activity}"]`);
                    if (cb) cb.checked = true;
                });
            }

            // Medications
            let savedMeds = [];
            if (data.medications_included) {
                try {
                     savedMeds = typeof data.medications_included === 'string' ? JSON.parse(data.medications_included) : data.medications_included;
                     if (!Array.isArray(savedMeds)) savedMeds = Object.values(savedMeds);
                } catch(e) { savedMeds = []; }
            }
            updateMedicationList(savedMeds);
            
            // UX: Update button text?
            const submitBtn = document.querySelector('button[value="complete"]');
            if (data.status === 'completed') {
               // warning or text change
               submitBtn.textContent = 'Update Submitted Report';
            } else {
               submitBtn.textContent = 'Submit Report';
            }
        }

        function getMoodEmoji(mood) {
             const moodMap = { 'Happy': '😄', 'Content': '😊', 'Fussy': '😐', 'Sad': '😢' };
             return moodMap[mood] || '';
        }

        // Attach listeners
        const childSelect = document.querySelector('select[name="child_id"]');
        if (childSelect) {
            childSelect.addEventListener('change', checkDraft);
        }
        
        const reportDateInput = document.getElementById('report_date');
        if (reportDateInput) {
            reportDateInput.addEventListener('change', checkDraft);
        }
        
        // Initial run
        document.addEventListener('DOMContentLoaded', () => { 
            setTimeout(() => {
                // If child is selected (e.g. browser autofill or back navigation), check draft
                if(childSelect.value && reportDateInput.value) {
                    checkDraft();
                } else {
                    updateMedicationList(); 
                }
            }, 100); 
        });

        function viewReport(reportId) {
            const report = reportsData.find(r => r.id === reportId);
            if (!report) return;

            const modal = document.getElementById('viewReportModal');
            const title = document.getElementById('modalReportTitle');
            const body = document.getElementById('modalReportBody');

            title.textContent = `${report.child.first_name} ${report.child.last_name} - ${new Date(report.report_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`;

            const meals = report.meals || {};
            const activities = report.activities || [];
            // Parse medication log if it's a string, otherwise use as object
            let medications = report.medications_included;
            if (typeof medications === 'string') {
                try {
                    medications = JSON.parse(medications);
                } catch(e) {
                    medications = [];
                }
            }
            // Ensure it's an array (handle object collection edge cases)
            medications = Array.isArray(medications) ? medications : (medications ? Object.values(medications) : []);
            
            // Filter only given medications
            const givenMedications = medications.filter(m => m.given == "1" || m.status == "Given");

            let medicationsHtml = '';
            if ( givenMedications.length > 0) {
                medicationsHtml = '<div class="medication-list-view">';
                givenMedications.forEach(m => {
                    let timeDisplay = m.time 
                        ? new Date(`2000-01-01T${m.time}`).toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'})
                        : 'Time not recorded';
                    
                    let doseLabel = m.dose_index ? `(Dose ${m.dose_index})` : '';

                    medicationsHtml += `
                        <div class="med-view-item" style="display: flex; justify-content: space-between; padding: 8px; border-bottom: 1px solid #eee;">
                            <div>
                                <strong class="text-gray-700">${m.medication_name}</strong>
                                <span class="text-xs text-gray-500">${doseLabel}</span>
                            </div>
                            <div class="text-green-600" style="color: #059669;">
                                <i class="fas fa-check-circle"></i> Given at ${timeDisplay}
                            </div>
                        </div>
                    `;
                });
                medicationsHtml += '</div>';
            } else {
                medicationsHtml = '<p class="text-gray-500 italic" style="color: #6b7280; font-style: italic;">No medications administered.</p>';
            }

            body.innerHTML = `
                <div class="report-detail-grid">
                    <div class="detail-section">
                        <h4 class="detail-heading">Mood</h4>
                        <p class="detail-value">${report.mood || 'Not recorded'}</p>
                    </div>

                    <div class="detail-section">
                        <h4 class="detail-heading">Meals</h4>
                        <ul class="meal-list">
                            <li><strong>Breakfast:</strong> ${meals.breakfast || 'Not recorded'}</li>
                            <li><strong>Lunch:</strong> ${meals.lunch || 'Not recorded'}</li>
                            <li><strong>Snack:</strong> ${meals.snack || 'Not recorded'}</li>
                        </ul>
                    </div>

                    <div class="detail-section">
                        <h4 class="detail-heading">Nap Time</h4>
                        <p class="detail-value">
                            ${report.nap_duration ? report.nap_duration + ' minutes' : 'No nap'} 
                            ${report.nap_quality ? ' - ' + report.nap_quality : ''}
                        </p>
                    </div>

                    <div class="detail-section">
                        <h4 class="detail-heading">Activities</h4>
                        <div class="activity-tags">
                            ${activities.length ? activities.map(a => `<span class="activity-tag">${a}</span>`).join('') : 'None recorded'}
                        </div>
                    </div>

                    <div class="detail-section full-width">
                        <h4 class="detail-heading">Medications Administered</h4>
                        <div class="medication-log-container" style="background: #f9fafb; padding: 10px; border-radius: 8px; border: 1px solid #e5e7eb;">
                            ${medicationsHtml}
                        </div>
                    </div>

                    <div class="detail-section full-width">
                        <h4 class="detail-heading">Notes & Observations</h4>
                        <p class="detail-value" style="white-space: pre-wrap;">${report.notes || 'No additional notes.'}</p>
                    </div>
                </div>
            `;

            modal.classList.add('active');
        }

        function formatTime(timeString) {
            if (!timeString) return '';
            const [hours, minutes] = timeString.split(':');
            const h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12 = h % 12 || 12;
            return `${h12}:${minutes} ${ampm}`;
        }

        function closeReportModal() {
            document.getElementById('viewReportModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('viewReportModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('viewReportModal')) {
                closeReportModal();
            }
        });

        function selectMood(mood, btn) {
            document.getElementById('selectedMood').value = mood;
            
            // Reset all buttons
            btn.parentElement.querySelectorAll('button').forEach(b => {
                b.style.borderColor = '#e5e7eb';
                b.style.background = 'white';
            });
            
            // Highlight selected
            btn.style.borderColor = '#059669';
            btn.style.background = '#d1fae5';
        }
    </script>
@endsection
