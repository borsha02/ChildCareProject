<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Activities - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css', 'resources/css/admin/activities.css'])
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">All System Activities</h1>
        </div>

        <div class="activity-card">
            <div class="activity-list">
                @forelse($activities as $activity)
                <div class="activity-item">
                    <div class="activity-icon {{ $activity['color'] == 'success' ? 'payment' : ($activity['color'] == 'orange' ? 'alert' : 'user') }}">
                        <i class="{{ $activity['icon'] }}"></i>
                    </div>
                    <div class="activity-content">
                        <h4>{{ $activity['title'] }}</h4>
                        <p>{{ $activity['description'] }}</p>
                    </div>
                    <div class="activity-time">{{ $activity['time']->diffForHumans() }}</div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-history" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <h3>No activities found</h3>
                    <p>System events will appear here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
