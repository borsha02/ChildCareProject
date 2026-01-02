<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Activities - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css'])
    <style>
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .back-btn {
            background: white;
            border: 1px solid #e5e7eb;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .back-btn:hover {
            background: #f9fafb;
            color: #1f2937;
            transform: translateX(-2px);
        }
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
        }
        .activity-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .empty-state {
            padding: 40px;
            text-align: center;
            color: #6b7280;
        }
    </style>
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
