<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Reports - {{ $date }}</title>
    @vite(['resources/css/admin/reports-pdf.css'])
</head>
<body onload="window.print()">
    
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; background: #fff; padding: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Print / Save as PDF</button>
    </div>

    @forelse($dailyReports as $report)
    <div class="report-page">
        <div class="header">
            <h1>Daily Activity Report</h1>
            <p>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</p>
        </div>

        <div class="child-info">
            <ul>
                <li><span class="label">Child Name:</span> <span class="value">{{ $report->child->first_name }} {{ $report->child->last_name }}</span></li>
                <li><span class="label">Class:</span> <span class="value">{{ $report->child->class }}</span></li>
                <li><span class="label">Caregiver:</span> <span class="value">{{ $report->caregiver->name }}</span></li>
            </ul>
        </div>

        @if($report->meals && count($report->meals) > 0)
        <div class="section">
            <div class="section-title">Meals</div>
            <ul>
                @foreach($report->meals as $meal)
                <li><span class="value">• {{ ucfirst($meal) }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($report->nap_duration)
        <div class="section">
            <div class="section-title">Nap Time</div>
            <ul>
                <li><span class="label">Duration:</span> <span class="value">{{ $report->nap_duration }} minutes</span></li>
                <li><span class="label">Quality:</span> <span class="value">{{ ucfirst($report->nap_quality) }}</span></li>
            </ul>
        </div>
        @endif

        @if($report->activities && count($report->activities) > 0)
        <div class="section">
            <div class="section-title">Activities</div>
            <ul>
                @foreach($report->activities as $activity)
                <li><span class="value">• {{ ucfirst($activity) }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if($report->mood)
        <div class="section">
            <div class="section-title">Mood</div>
            <ul>
                <li><span class="label">Observation:</span> <span class="value">{{ ucfirst($report->mood) }}</span></li>
            </ul>
        </div>
        @endif

        @if($report->notes)
        <div class="section">
            <div class="section-title">Caregiver Notes</div>
            <p style="white-space: pre-wrap;">{{ $report->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Generated on {{ now()->format('Y-m-d H:i') }} | Childcare Management System</p>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 50px;">
        <h2>No reports found for this date.</h2>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer;">Go Back</button>
    </div>
    @endforelse

</body>
</html>
