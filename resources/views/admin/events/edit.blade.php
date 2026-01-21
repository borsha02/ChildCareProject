<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin Panel</title>
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
                </div>
            </div>

            <div class="content-area">
                <div class="header-actions">
                    <h1>Edit Event: {{ $event->title }}</h1>
                    <div class="actions">
                        <a href="{{ route('admin.events.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card">
                    <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="title">Event Title <span class="required">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $event->title) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category <span class="required">*</span></label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="general" {{ $event->category == 'general' ? 'selected' : '' }}>General</option>
                                        <option value="educational" {{ $event->category == 'educational' ? 'selected' : '' }}>Educational</option>
                                        <option value="sports" {{ $event->category == 'sports' ? 'selected' : '' }}>Sports</option>
                                        <option value="cultural" {{ $event->category == 'cultural' ? 'selected' : '' }}>Cultural</option>
                                        <option value="social" {{ $event->category == 'social' ? 'selected' : '' }}>Social</option>
                                        <option value="training" {{ $event->category == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="holiday" {{ $event->category == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="audience">Audience <span class="required">*</span></label>
                                    <select name="audience" id="audience" class="form-control" required>
                                        <option value="all" {{ $event->audience == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="parent" {{ $event->audience == 'parent' ? 'selected' : '' }}>Parents Only</option>
                                        <option value="caregiver" {{ $event->audience == 'caregiver' ? 'selected' : '' }}>Caregivers Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Start Time <span class="required">*</span></label>
                                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" required value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">End Time <span class="required">*</span></label>
                                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" required value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $event->location) }}">
                        </div>

                        <div class="form-group">
                            <label for="capacity">Capacity (Optional)</label>
                            <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity', $event->capacity) }}" min="1">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Update Event</button>
                        </div>
                    </form>
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
