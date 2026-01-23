<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report - {{ $child->first_name }} {{ $child->last_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #10b981;
        }
        .header h1 {
            font-size: 24px;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #64748b;
        }
        .summary-box {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .summary-row {
            display: flex;
            margin-bottom: 8px;
        }
        .summary-row:last-child {
            margin-bottom: 0;
        }
        .summary-label {
            font-weight: bold;
            color: #64748b;
            width: 120px;
        }
        .summary-value {
            color: #1e293b;
            flex: 1;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
        }
        .section-content {
            padding: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .meal-list, .activity-list {
            list-style: none;
            padding: 0;
        }
        .meal-list li, .activity-list li {
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .meal-list li:last-child, .activity-list li:last-child {
            border-bottom: none;
        }
        .meal-list strong {
            color: #1e293b;
            display: inline-block;
            width: 80px;
        }
        .medication-item {
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .medication-item:last-child {
            margin-bottom: 0;
        }
        .med-name {
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 3px;
        }
        .med-time {
            color: #10b981;
            font-size: 11px;
        }
        .notes-text {
            padding: 10px;
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 4px;
            color: #78350f;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
        .grid-2col {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .grid-col {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }
        .grid-col:first-child {
            padding-right: 2%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Report</h1>
        <p>{{ \Carbon\Carbon::parse($report->report_date)->format('l, F d, Y') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Child Name:</span>
            <span class="summary-value">{{ $child->first_name }} {{ $child->last_name }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Class:</span>
            <span class="summary-value">{{ $child->class }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Caregiver:</span>
            <span class="summary-value">{{ $caregiver ? $caregiver->name : 'Unknown' }}</span>
        </div>
    </div>

    <div class="grid-2col">
        <div class="grid-col">
            <div class="section">
                <div class="section-title">Mood</div>
                <div class="section-content">
                    {{ $report->mood ?: 'Not recorded' }}
                </div>
            </div>
        </div>
        <div class="grid-col">
            <div class="section">
                <div class="section-title">Nap Time</div>
                <div class="section-content">
                    @if($report->nap_duration)
                        {{ $report->nap_duration }} minutes
                        @if($report->nap_quality)
                            - {{ $report->nap_quality }}
                        @endif
                    @else
                        Not recorded
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Meals</div>
        <div class="section-content">
            @if($report->meals && is_array($report->meals))
                <ul class="meal-list">
                    @foreach($report->meals as $meal => $status)
                        <li><strong>{{ ucfirst($meal) }}:</strong> {{ $status }}</li>
                    @endforeach
                </ul>
            @else
                No meals recorded
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Activities</div>
        <div class="section-content">
            @if($report->activities && is_array($report->activities) && count($report->activities) > 0)
                <ul class="activity-list">
                    @foreach($report->activities as $activity)
                        <li>• {{ is_array($activity) ? ($activity['name'] ?? 'Unknown Activity') : $activity }}</li>
                    @endforeach
                </ul>
            @else
                No activities recorded
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Medications Administered</div>
        <div class="section-content">
            @if($report->medications_included && is_array($report->medications_included) && count($report->medications_included) > 0)
                @foreach($report->medications_included as $medication)
                    @php
                        $medName = is_array($medication) ? ($medication['medication_name'] ?? $medication['name'] ?? 'Unknown') : $medication;
                        $medTime = is_array($medication) && isset($medication['time']) ? $medication['time'] : null;
                        $doseIndex = is_array($medication) && isset($medication['dose_index']) ? $medication['dose_index'] : null;
                        $amount = is_array($medication) && isset($medication['amount']) ? $medication['amount'] : null;
                        
                        // Build dose info
                        $doseInfo = '';
                        if ($doseIndex || $amount) {
                            $doseParts = [];
                            if ($doseIndex) $doseParts[] = "Dose $doseIndex";
                            if ($amount) $doseParts[] = $amount;
                            $doseInfo = ' (' . implode(' - ', $doseParts) . ')';
                        }
                    @endphp
                    <div class="medication-item">
                        <div class="med-name">{{ $medName }}{{ $doseInfo }}</div>
                        @if($medTime)
                            <div class="med-time">✓ Given at {{ $medTime }}</div>
                        @endif
                    </div>
                @endforeach
            @else
                <p>No medications administered</p>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Notes & Observations</div>
        <div class="section-content">
            <div class="notes-text">
                {{ $report->notes ?: 'No additional notes.' }}
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>{{ $settings['system_name'] ?? 'Childcare Management System' }}</p>
    </div>
</body>
</html>
