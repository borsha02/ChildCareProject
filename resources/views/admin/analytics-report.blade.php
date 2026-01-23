<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Report - {{ $reportData['start_date'] }} to {{ $reportData['end_date'] }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
            background: #fff;
        }
        .report-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .report-title {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        .report-meta {
            color: #6b7280;
            font-size: 14px;
        }
        .report-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
            .stat-card { border: 1px solid #ddd; }
        }
        .btn-print {
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-print:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Print Report
        </button>
        <a href="{{ route('admin.analytics') }}" class="btn-print" style="background: #6b7280; margin-left: 10px;">Back to Dashboard</a>
    </div>

    <div class="report-header">
        <h1 class="report-title">Analytics Report</h1>
        <div class="report-meta">
            Period: {{ $reportData['start_date'] }} - {{ $reportData['end_date'] }}<br>
            Generated on: {{ $reportData['generated_at'] }}
        </div>
    </div>

    <div class="report-grid">
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">${{ number_format($reportData['revenue'], 2) }}</div>
            <div style="margin-top: 5px; font-size: 13px; color: #666;">Approved payments only</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Average Attendance</div>
            <div class="stat-value">{{ $reportData['avg_attendance'] }}%</div>
            <div style="margin-top: 5px; font-size: 13px; color: #666;">Based on {{ $reportData['total_active_children'] }} active children</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">New Registrations</div>
            <div class="stat-value">{{ $reportData['new_registrations'] }}</div>
            <div style="margin-top: 5px; font-size: 13px; color: #666;">children enrolled in period</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Feedback Received</div>
            <div class="stat-value">{{ $reportData['new_feedback_count'] }}</div>
            <div style="margin-top: 5px; font-size: 13px; color: #666;">Avg Rating: {{ number_format($reportData['avg_feedback_rating'], 1) }} / 5.0</div>
        </div>
    </div>

    <div class="footer">
        Preschool Management System &copy; {{ date('Y') }}
    </div>

    <script>
        // Auto print prompt
        window.onload = function() {
            // setTimeout(() => window.print(), 500); 
        }
    </script>
</body>
</html>
