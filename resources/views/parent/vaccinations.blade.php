<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Records - Childcare Management</title>
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
                    <h1><i class="fas fa-syringe"></i> Vaccination Records</h1>
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
                            <p style="color: #9ca3af;">Add a child profile to view vaccination records</p>
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
                            This child's registration is currently under review by the administration. Vaccination records will be available once the registration is approved.
                        </p>
                        <div style="display: flex; gap: 10px; font-size: 14px; color: #4b5563; background: #f3f4f6; padding: 10px 20px; border-radius: 20px;">
                            <i class="fas fa-info-circle" style="color: #4f46e5; margin-top: 2px;"></i>
                            <span>You will receive a notification when approved.</span>
                        </div>
                    </div>

                    <!-- Vaccination Records -->
                    <div id="vaccinationContentContainer" style="display: {{ !$children->first() || $children->first()->status !== 'pending' ? 'block' : 'none' }}">
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-syringe"></i> All Vaccination Records</h2>
                                <button class="add-btn" onclick="openVaccinationModal()">
                                    <i class="fas fa-plus"></i> Add Record
                                </button>
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
                                            @if($vaccination->notes)
                                            <p class="vaccine-notes"><strong>Notes:</strong> {{ $vaccination->notes }}</p>
                                            @endif
                                        </div>
                                        <span class="vaccine-status {{ $vaccination->status }}">{{ ucfirst($vaccination->status) }}</span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="empty-state" style="padding: 2rem; text-align: center;">
                                        <i class="fas fa-syringe" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                        <p style="color: #6b7280;">No vaccination records found</p>
                                        <button class="add-btn" onclick="openVaccinationModal()" style="margin-top: 1rem;">
                                            <i class="fas fa-plus"></i> Add First Record
                                        </button>
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
                    const contentContainer = document.getElementById('vaccinationContentContainer');
                    
                    if (child.status === 'pending') {
                        pendingContainer.style.display = 'flex';
                        if (contentContainer) contentContainer.style.display = 'none';
                        return;
                    } else {
                        pendingContainer.style.display = 'none';
                        if (contentContainer) contentContainer.style.display = 'block';
                    }

                    // Update vaccination list
                    updateVaccinationList(child.vaccinations);
                    // Update hidden input
                    document.getElementById('vaccination_child_id').value = childId;
                }
            });
        });

        function updateVaccinationList(vaccinations) {
            const listContainer = document.getElementById('vaccinationList');
            
            if (!vaccinations || vaccinations.length === 0) {
                listContainer.innerHTML = `
                    <div class="empty-state" style="padding: 2rem; text-align: center;">
                        <i class="fas fa-syringe" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                        <p style="color: #6b7280;">No vaccination records found</p>
                        <button class="add-btn" onclick="openVaccinationModal()" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i> Add First Record
                        </button>
                    </div>
                `;
                return;
            }

            let html = '';
            vaccinations.forEach(vaccination => {
                const icon = vaccination.status === 'completed' ? 'fa-check-circle' : (vaccination.status === 'upcoming' ? 'fa-clock' : 'fa-exclamation-triangle');
                const date = vaccination.administered_date 
                    ? `Administered: ${new Date(vaccination.administered_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`
                    : vaccination.scheduled_date 
                    ? `Scheduled: ${new Date(vaccination.scheduled_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`
                    : '';
                
                html += `
                    <div class="vaccination-item ${vaccination.status}">
                        <div class="vaccine-icon">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div class="vaccine-info">
                            <h4>${vaccination.vaccine_name}</h4>
                            <p>${vaccination.description || 'No description'}</p>
                            <span class="vaccine-date">${date}</span>
                            ${vaccination.notes ? `<p class="vaccine-notes"><strong>Notes:</strong> ${vaccination.notes}</p>` : ''}
                        </div>
                        <span class="vaccine-status ${vaccination.status}">${vaccination.status.charAt(0).toUpperCase() + vaccination.status.slice(1)}</span>
                    </div>
                `;
            });
            
            listContainer.innerHTML = html;
        }

        function openVaccinationModal() {
            if (childrenData.length === 0) {
                alert('Please add a child profile first.');
                return;
            }
            const modal = document.getElementById('vaccinationModal');
            modal.style.display = 'flex';
            modal.classList.add('active');
        }

        function closeVaccinationModal() {
            const modal = document.getElementById('vaccinationModal');
            modal.style.display = 'none';
            modal.classList.remove('active');
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
