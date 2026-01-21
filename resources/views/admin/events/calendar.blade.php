<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Calendar - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    @vite(['resources/css/admin/dashboard.css', 'resources/css/admin/sidebar.css', 'resources/css/admin/events.css'])
    
    <style>
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 800px;
        }
        .fc-event {
            cursor: pointer;
        }
    </style>
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
                    <a href="{{ route('admin.events.index') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Events Management</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search events...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <div class="header-actions">
                    <h1>Events Calendar</h1>
                    <div class="actions">
                        <button id="bulkSaveBtn" class="btn-primary" style="display: none;">
                            <i class="fas fa-save"></i> Save Selected Holidays
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn-secondary">
                            <i class="fas fa-list"></i> List View
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="mb-3" style="margin-bottom: 1rem; color: #6b7280;">
                        <p><i class="fas fa-info-circle"></i> Click on a date to toggle it as a Holiday. Click "Save" to create events for selected dates.</p>
                    </div>
                    <div id='calendar'></div>
                </div>
            </div>
        </main>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var selectedDates = [];
            var bulkSaveBtn = document.getElementById('bulkSaveBtn');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($events as $event)
                    {
                        title: '{{ $event->title }}',
                        start: '{{ $event->start_time->format("Y-m-d\TH:i:s") }}',
                        end: '{{ $event->end_time->format("Y-m-d\TH:i:s") }}',
                        color: '{{ $event->category == "holiday" ? "#dc3545" : "#3788d8" }}',
                        url: '{{ route("admin.events.edit", $event->id) }}'
                    },
                    @endforeach
                ],
                dateClick: function(info) {
                    // Check if date is already selected
                    var dateStr = info.dateStr;
                    var index = selectedDates.indexOf(dateStr);

                    if (index > -1) {
                        // Deselect
                        selectedDates.splice(index, 1);
                        var event = calendar.getEventById('temp-' + dateStr);
                        if (event) event.remove();
                    } else {
                        // Select
                        selectedDates.push(dateStr);
                        calendar.addEvent({
                            id: 'temp-' + dateStr,
                            title: 'Holiday (New)',
                            start: dateStr,
                            allDay: true,
                            color: '#28a745',
                            display: 'background'
                        });
                    }

                    // Show/Hide save button
                    if (selectedDates.length > 0) {
                        bulkSaveBtn.style.display = 'inline-block';
                    } else {
                        bulkSaveBtn.style.display = 'none';
                    }
                }
            });

            calendar.render();

            bulkSaveBtn.addEventListener('click', function() {
                if (selectedDates.length === 0) return;

                if (!confirm('Create holiday events for ' + selectedDates.length + ' selected dates?')) return;

                var eventsToCreate = selectedDates.map(function(date) {
                    return {
                        title: 'Holiday',
                        start: date,
                        type: 'holiday'
                    };
                });

                fetch('{{ route("admin.events.bulk-store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ events: eventsToCreate })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Holidays created successfully!');
                        window.location.reload();
                    } else {
                        alert('Error creating holidays.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred.');
                });
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
                    if (sidebar && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>
</html>
