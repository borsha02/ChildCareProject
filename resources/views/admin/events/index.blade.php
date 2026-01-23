<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css', 'resources/css/admin/sidebar.css', 'resources/css/admin/events.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

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
                    <h1>Events Management</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search events...">
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
                <div class="header-actions">
                    <h1>Manage Events</h1>
                    <div class="actions">
                        <a href="{{ route('admin.events.calendar') }}" class="btn-secondary">
                            <i class="fas fa-calendar-alt"></i> Calendar View
                        </a>
                        <a href="{{ route('admin.events.create') }}" class="btn-primary">
                            <i class="fas fa-plus"></i> Create Event
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success" style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #a7f3d0;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Audience</th>
                                    <th>Attendees</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>
                                            {{ $event->start_time->format('M d, Y') }}<br>
                                            <small style="color: #6b7280;">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <strong style="color: #1f2937;">{{ $event->title }}</strong><br>
                                            <small style="color: #6b7280;">{{ Str::limit($event->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $event->category }}">
                                                {{ ucfirst($event->category) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $event->audience }}">
                                                {{ ucfirst($event->audience) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $event->registrations_count ?? 0 }} / {{ $event->capacity ?? '∞' }}
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon view" title="View Registrations" onclick="viewRegistrations({{ $event->id }})">
                                                    <i class="fas fa-users"></i>
                                                </button>
                                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-icon" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-icon delete" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center" style="text-align: center; color: #6b7280;">No events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Registration List Section (Inline) -->
                <div id="registrationListSection" class="card" style="display: none; border-top: 4px solid #6366f1;">
                    <div class="registration-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 id="registrationTitle" style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Event Registrations</h2>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn-icon" id="downloadRegPdfBtn" onclick="downloadEventRegistrationsPDF()" title="Download PDF" style="color: #6366f1;">
                                <i class="fas fa-file-pdf" style="font-size: 1.25rem;"></i>
                            </button>
                            <button class="btn-icon" onclick="closeRegistrationList()" title="Close">
                                <i class="fas fa-times" style="font-size: 1.25rem;"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="registration-summary" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                        <div class="summary-item" style="background: #f0f9ff; padding: 1rem; border-radius: 8px; border: 1px solid #bae6fd;">
                            <div style="font-size: 0.75rem; color: #0369a1; text-transform: uppercase; font-weight: 600;">Total Registrations</div>
                            <div id="summaryTotal" style="font-size: 1.5rem; font-weight: 700; color: #0c4a6e;">0</div>
                        </div>
                        <div class="summary-item" style="background: #f0fdf4; padding: 1rem; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <div style="font-size: 0.75rem; color: #15803d; text-transform: uppercase; font-weight: 600;">Total Parents</div>
                            <div id="summaryParents" style="font-size: 1.5rem; font-weight: 700; color: #064e3b;">0</div>
                        </div>
                        <div class="summary-item" style="background: #fdf2f2; padding: 1rem; border-radius: 8px; border: 1px solid #fecaca;">
                            <div style="font-size: 0.75rem; color: #991b1b; text-transform: uppercase; font-weight: 600;">Total Children</div>
                            <div id="summaryChildren" style="font-size: 1.5rem; font-weight: 700; color: #7f1d1d;">0</div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Parent Name</th>
                                    <th>Child Name</th>
                                    <th>Child ID</th>
                                    <th>Registration Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="registrationListBody">
                                <!-- Data will be loaded via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
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
                if (sidebar && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Registration List Logic
        let currentEventId = null;

        async function viewRegistrations(eventId) {
            currentEventId = eventId;
            const section = document.getElementById('registrationListSection');
            const body = document.getElementById('registrationListBody');
            const title = document.getElementById('registrationTitle');
            
            // Stats elements
            const totalEl = document.getElementById('summaryTotal');
            const parentsEl = document.getElementById('summaryParents');
            const childrenEl = document.getElementById('summaryChildren');

            // Show section with loading state
            body.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">Loading registrations...</td></tr>';
            section.style.display = 'block';
            
            // Scroll to section
            setTimeout(() => {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);

            try {
                const response = await fetch(`/admin/events/${eventId}/registrations`);
                const data = await response.json();

                title.textContent = `Registrations: ${data.event_title}`;
                
                // Update Summary
                totalEl.textContent = data.total_registrations;
                parentsEl.textContent = data.total_parents;
                childrenEl.textContent = data.total_children;

                if (data.registrations.length === 0) {
                    body.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem; color: #6b7280;">No registrations found for this event.</td></tr>';
                } else {
                    body.innerHTML = data.registrations.map(reg => `
                        <tr>
                            <td><strong>${reg.parent_name}</strong></td>
                            <td>${reg.child_name}</td>
                            <td><span style="color: #6366f1; font-weight: 500;">${reg.child_id}</span></td>
                            <td>${reg.registered_at}</td>
                            <td><span class="badge" style="background: #f3f4f6; color: #374151;">${reg.status}</span></td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error fetching registrations:', error);
                body.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem; color: #ef4444;">Error loading registrations. Please try again.</td></tr>';
            }
        }

        function downloadEventRegistrationsPDF() {
            if (currentEventId) {
                window.open(`/admin/events/${currentEventId}/export-pdf`, '_blank');
            }
        }

        function closeRegistrationList() {
            document.getElementById('registrationListSection').style.display = 'none';
        }
    </script>
</body>
</html>
