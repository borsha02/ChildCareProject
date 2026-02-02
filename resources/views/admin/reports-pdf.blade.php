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
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
             Save / Print PDF
        </button>
    </div>

    @forelse($dailyReports as $report)
    <div class="report-page">
        <!-- Header -->
        <div class="header">
            <h1>Daily Activity Report</h1>
            <p>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</p>
        </div>

        <!-- Child Summary -->
        <div class="child-info">
            <div class="info-item">
                <span class="label">Child Name</span>
                <span class="value">{{ $report->child->first_name }} {{ $report->child->last_name }}</span>
            </div>
            <div class="info-item">
                <span class="label">Class</span>
                <span class="value">{{ $report->child->class }}</span>
            </div>
            <div class="info-item">
                <span class="label">Caregiver</span>
                <span class="value">{{ $report->caregiver->name }}</span>
            </div>
        </div>

        <div class="grid-2-col">
            <!-- Mood and Nap side by side -->
            <div>
                @if($report->mood)
                <div class="section">
                    <div class="section-title">Mood</div>
                    <div style="font-size: 15px; font-weight: 500; color: #334155; padding: 5px 0;">
                        {{ ucfirst($report->mood) }}
                    </div>
                </div>
                @endif
            </div>

            <div>
                @if($report->nap_duration)
                <div class="section">
                    <div class="section-title">Nap Time</div>
                    <div style="font-size: 14px; color: #334155;">
                        <span style="font-weight: 600;">{{ $report->nap_duration }} minutes</span> 
                        @if($report->nap_quality)
                            - {{ ucfirst($report->nap_quality) }}
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Meals Section -->
        @php
            $meals = $report->meals;
            // Handle if it's a JSON string
            if(is_string($meals)) {
                $meals = json_decode($meals, true);
            }
        @endphp

        @if($meals && (is_array($meals) || is_object($meals)))
        <div class="section">
            <div class="section-title">Meals</div>
            <ul class="meal-list">
                @if(is_array($meals) && array_values($meals) === $meals) 
                    {{-- Array list (old format or simple list) --}}
                    @foreach($meals as $meal)
                        <li><span>• {{ ucfirst($meal) }}</span></li>
                    @endforeach
                @else
                    {{-- Object/Assoc Array (smart format) --}}
                    @foreach($meals as $type => $status)
                        <li>
                            <span style="font-weight: 600; color: #475569;">{{ ucfirst($type) }}:</span>
                            <span style="color: #0f172a;">{{ $status }}</span>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
        @endif

        <!-- Activities Section -->
        @php
            $activities = $report->activities;
            if(is_string($activities)) {
                $activities = json_decode($activities, true);
            }
        @endphp

        @if($activities && count($activities) > 0)
        <div class="section">
            <div class="section-title">Activities</div>
            <div class="activity-tags">
                @foreach($activities as $activity)
                    @php
                        $activityName = is_array($activity) ? ($activity['name'] ?? 'Unknown') : $activity;
                    @endphp
                    <span class="activity-tag">{{ $activityName }}</span>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Medications Section -->
        @php
            $medications = $report->medications_included;
             if (!$medications && isset($report->medications)) {
                $medications = $report->medications;
            }
            if(is_string($medications)) {
                $medications = json_decode($medications, true);
            }
        @endphp

        @if($medications && is_array($medications) && count($medications) > 0)
        <div class="section">
            <div class="section-title">Medications Administered</div>
            <div class="medication-list">
                @foreach($medications as $med)
                     @php
                        $medName = is_array($med) ? ($med['medication_name'] ?? $med['name'] ?? 'Unknown') : $med;
                        $medTime = is_array($med) && isset($med['time']) ? $med['time'] : null;
                        $medAmount = is_array($med) && isset($med['amount']) ? $med['amount'] : null;
                    @endphp
                    <div class="medication-item">
                        <span style="font-weight: 600;">{{ $medName }} {{ $medAmount ? "($medAmount)" : '' }}</span>
                        @if($medTime)
                            <span style="font-size: 13px;">Given at: {{ $medTime }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Notes Section -->
        @if($report->notes)
        <div class="section">
            <div class="section-title">Caregiver Notes</div>
            <div class="notes-box">{{ $report->notes }}</div>
        </div>
        @endif

        <div class="footer">
            <p>Generated on <span id="printTime"></span> | Little Stars Childcare Center</p>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 100px 20px;">
        <h2 style="color: #64748b;">No daily reports found for {{ $date }}.</h2>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer; margin-top: 20px; background: #e2e8f0; border: none; border-radius: 6px;">Go Back</button>
    </div>
    @endforelse

    <script>
        // Set the current print time
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const timestamp = `${year}-${month}-${day} ${hours}:${minutes}`;
        document.querySelectorAll('#printTime').forEach(el => el.textContent = timestamp);
    </script>
</body>
</html>
