<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Ratings - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css', 'resources/css/admin/sidebar.css', 'resources/css/admin/ratings.css'])
    <style>
        .ratings-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .ratings-table th, .ratings-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .ratings-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #475569;
        }
        .star-rating {
            color: #f59e0b;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        @include('admin.partials.sidebar')

        <main class="main-content">
            <div class="top-bar">
                <div style="display: flex; align-items: center;">
                     <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon" style="margin-right: 15px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.1); color: #4a5568; text-decoration: none; transition: all 0.2s ease;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Caregiver Ratings</h1>
                </div>
            </div>

            <div class="content-area">
                <table class="ratings-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Parent</th>
                            <th>Caregiver</th>
                            <th>Rating</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ratings as $rating)
                        <tr>
                            <td>{{ $rating->created_at->format('M d, Y') }}</td>
                            <td>{{ $rating->parent->name }}</td>
                            <td>{{ $rating->caregiver->name }}</td>
                            <td>
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>{{ $rating->comment ?? 'No comment' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b;">No ratings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
