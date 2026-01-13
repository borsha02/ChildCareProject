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
        @include('admin.partials.sidebar', ['stats' => $stats ?? [], 'pendingJobAppsCount' => $pendingJobAppsCount ?? 0])

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
    </script>
</body>
</html>
