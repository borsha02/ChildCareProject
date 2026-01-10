<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ratings - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/caregiver/dashboard.css'])
    <style>
        .ratings-container {
            padding: 20px;
        }
        .rating-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 5px solid #3b82f6;
        }
        .rating-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .rating-date {
            color: #64748b;
            font-size: 0.9em;
        }
        .star-rating {
            color: #f59e0b;
            font-size: 1.2em;
        }
        .rating-comment {
            color: #334155;
            line-height: 1.6;
            font-style: italic;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Caregiver Panel</h2>
                </div>
            </div>
            
            <nav class="nav-menu">
                <a href="{{ route('caregiver.dashboard') }}" class="nav-item">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('caregiver.children') }}" class="nav-item">
                    <i class="fas fa-child"></i> <span>Assigned Children</span>
                </a>
                <a href="{{ route('caregiver.attendance') }}" class="nav-item">
                    <i class="fas fa-calendar-check"></i> <span>Attendance</span>
                </a>
                <a href="{{ route('caregiver.reports') }}" class="nav-item">
                    <i class="fas fa-file-alt"></i> <span>Daily Reports</span>
                </a>
                <a href="{{ route('caregiver.ratings') }}" class="nav-item active">
                    <i class="fas fa-star"></i> <span>My Ratings</span>
                </a>
                <!-- Other links would go here -->
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                 <div style="display: flex; align-items: center;">
                    <h1>My Ratings & Reviews</h1>
                </div>
                <!-- Profile/Notifications -->
            </div>

            <div class="content-area">
                <div class="ratings-container">
                    @forelse($ratings as $rating)
                    <div class="rating-card">
                        <div class="rating-header">
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span style="font-size: 0.8em; color: #64748b; margin-left: 5px;">({{ $rating->rating }}/5)</span>
                            </div>
                            <span class="rating-date">{{ $rating->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($rating->comment)
                        <div class="rating-comment">
                            "{{ $rating->comment }}"
                        </div>
                        @else
                        <div style="color: #94a3b8; font-style: italic;">No specific comment provided.</div>
                        @endif
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-star" style="font-size: 48px; margin-bottom: 20px; color: #e2e8f0;"></i>
                        <h3>No Ratings Yet</h3>
                        <p>Ratings from parents will appear here.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>
