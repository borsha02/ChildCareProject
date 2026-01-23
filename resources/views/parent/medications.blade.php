<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medications - Childcare Management</title>
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
            <!-- Header -->
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('parent.health') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1><i class="fas fa-pills"></i> Current Medications</h1>
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

            <!-- Main Content Area -->
            <div class="content-body">
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
                            <p style="color: #9ca3af;">Add a child profile to view medications</p>
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
                            This child's registration is currently under review by the administration. Medication records will be available once the registration is approved.
                        </p>
                        <div style="display: flex; gap: 10px; font-size: 14px; color: #4b5563; background: #f3f4f6; padding: 10px 20px; border-radius: 20px;">
                            <i class="fas fa-info-circle" style="color: #4f46e5; margin-top: 2px;"></i>
                            <span>You will receive a notification when approved.</span>
                        </div>
                    </div>

                    <!-- Medications Table -->
                    <div id="medicationContentContainer" style="display: {{ !$children->first() || $children->first()->status !== 'pending' ? 'block' : 'none' }}">
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-pills"></i> All Medications</h2>
                                <button class="add-btn" onclick="openMedicationModal()">
                                    <i class="fas fa-plus"></i> Add Medication
                                </button>
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
                                        @if($medication->notes)
                                        <tr class="notes-row">
                                            <td colspan="7">
                                                <strong>Notes:</strong> {{ $medication->notes }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                                <i class="fas fa-pills" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem; display: block;"></i>
                                                <p style="color: #6b7280;">No medications found</p>
                                                <button class="add-btn" onclick="openMedicationModal()" style="margin-top: 1rem;">
                                                    <i class="fas fa-plus"></i> Add First Medication
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
                    <button type="submit" class="btn-submit" id="medicationSubmitBtn">
                        <i class="fas fa-save"></i> Save Medication
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const childrenData = @json($children);

        // Child tab switching
        document.querySelectorAll('.child-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.child-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const childId = parseInt(this.getAttribute('data-child'));
                const child = childrenData.find(c => c.id === childId);
                
                if (child) {
                    const pendingContainer = document.getElementById('pendingMessageContainer');
                    const contentContainer = document.getElementById('medicationContentContainer');
                    
                    if (child.status === 'pending') {
                        pendingContainer.style.display = 'flex';
                        if (contentContainer) contentContainer.style.display = 'none';
                        return;
                    } else {
                        pendingContainer.style.display = 'none';
                        if (contentContainer) contentContainer.style.display = 'block';
                    }

                    updateMedicationTable(child.medications);
                    document.getElementById('medication_child_id').value = childId;
                }
            });
        });

        function updateMedicationTable(medications) {
            const tableBody = document.getElementById('medicationTableBody');
            
            if (!medications || medications.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <i class="fas fa-pills" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem; display: block;"></i>
                            <p style="color: #6b7280;">No medications found</p>
                            <button class="add-btn" onclick="openMedicationModal()" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Add First Medication
                            </button>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            medications.forEach(medication => {
                const endDate = medication.end_date 
                    ? new Date(medication.end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                    : 'Ongoing';
                
                html += `
                    <tr>
                        <td>
                            <div class="medication-name">
                                <i class="fas fa-capsules"></i>
                                <span>${medication.medication_name}</span>
                            </div>
                        </td>
                        <td>${medication.dosage}</td>
                        <td>${medication.frequency}</td>
                        <td>${new Date(medication.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                        <td>${endDate}</td>
                        <td><span class="status-badge ${medication.status}">${medication.status.charAt(0).toUpperCase() + medication.status.slice(1)}</span></td>
                        <td>
                            <button class="action-icon-btn" title="Edit" onclick="editMedication(${medication.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-icon-btn" title="Delete" onclick="deleteMedication(${medication.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                if (medication.notes) {
                    html += `
                        <tr class="notes-row">
                            <td colspan="7">
                                <strong>Notes:</strong> ${medication.notes}
                            </td>
                        </tr>
                    `;
                }
            });
            
            tableBody.innerHTML = html;
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

            // Default start date to today
            document.getElementById('start_date').value = new Date().toLocaleDateString('en-CA');

            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function editMedication(medicationId) {
            const childId = parseInt(document.querySelector('.child-tab.active').getAttribute('data-child'));
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
            form.action = `/parent/health/medication/${medication.id}`;

            document.getElementById('medication_name').value = medication.medication_name;
            document.getElementById('dosage').value = medication.dosage;
            document.getElementById('frequency').value = medication.frequency;
            document.getElementById('start_date').value = medication.start_date;
            document.getElementById('end_date').value = medication.end_date || '';
            document.getElementById('medication_status').value = medication.status;
            document.getElementById('medication_notes').value = medication.notes || '';

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

        function closeMedicationModal() {
            const modal = document.getElementById('medicationModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
                e.target.classList.remove('active');
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
