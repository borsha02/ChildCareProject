<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Actions - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/dashboard.css'])
    <style>
        .container {
            max-width: 1000px;
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
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pending-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
        }
        .action-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .action-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .action-item:hover {
            border-color: #d1d5db;
            background: #f3f4f6;
        }
        .item-info h4 {
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .item-info p {
            font-size: 14px;
            color: #6b7280;
        }
        .item-actions {
            display: flex;
            gap: 10px;
        }
        .btn-approve {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-approve:hover {
            background: #059669;
        }
        .btn-reject {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-reject:hover {
            background: #dc2626;
        }
        .empty-text {
            color: #9ca3af;
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Pending Actions</h1>
        </div>

        <!-- Child Registrations -->
        <div class="pending-card">
            <div class="section-title">
                <span><i class="fas fa-child" style="color: #10b981; margin-right: 10px;"></i> Child Registration Requests</span>
                <span class="badge" style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 12px;">{{ $pendingChildren->count() }}</span>
            </div>
            <div class="action-list">
                @forelse($pendingChildren as $child)
                <div class="action-item">
                    <div class="item-info">
                        <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                        <p>Parent: {{ $child->parent->name ?? 'Unknown' }} • Submitted {{ $child->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="item-actions">
                        <form action="{{ route('admin.children.approve', $child->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve">Approve</button>
                        </form>
                        <form action="{{ route('admin.children.reject', $child->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to reject this registration?')">Reject</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="empty-text">No pending child registrations.</div>
                @endforelse
            </div>
        </div>

        <!-- Staff Applications -->
        <div class="pending-card">
            <div class="section-title">
                <span><i class="fas fa-briefcase" style="color: #f59e0b; margin-right: 10px;"></i> Staff Applications</span>
                <span class="badge" style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 12px;">{{ $pendingApplications->count() }}</span>
            </div>
            <div class="action-list">
                @forelse($pendingApplications as $app)
                <div class="action-item">
                    <div class="item-info">
                        <h4>{{ $app->full_name }}</h4>
                        <p>Position: {{ $app->position }} • Applied {{ $app->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="item-actions">
                        <!-- Redirect to staff page for detailed review/creation -->
                        <a href="{{ route('admin.staff') }}" class="btn-approve" style="text-decoration: none; display: inline-block;">Review</a>
                        <form action="{{ route('admin.jobs.reject', $app->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to reject this application?')">Reject</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="empty-text">No pending staff applications.</div>
                @endforelse
            </div>
        </div>

        <!-- Payments (Placeholder) -->
        <div class="pending-card">
            <div class="section-title">
                <span><i class="fas fa-credit-card" style="color: #3b82f6; margin-right: 10px;"></i> Payment Approvals</span>
                <span class="badge" style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 12px;">0</span>
            </div>
            <div class="action-list">
                <div class="empty-text">No pending payments.</div>
            </div>
        </div>
    </div>
</body>
</html>
