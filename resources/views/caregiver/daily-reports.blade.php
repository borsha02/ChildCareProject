@extends('layouts.caregiver')

@section('title', 'Daily Reports')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Daily Reports</h1>
        <div class="top-bar-actions">
            <button onclick="document.getElementById('create-report-form').scrollIntoView({behavior: 'smooth'})"
                style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-plus"></i> Create New Report
            </button>
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="notification-dot"></span>
            </a>
            <a href="{{ route('caregiver.messages') }}"
                class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedChildren->count() }}</h3>
                    <p>Reports Today</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <!-- Logic for pending reports: assigned children count - today's reports count -->
                <div class="stat-details">
                    <h3>{{ $pendingReportsCount }}</h3>
                    <p>Pending Reports</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $completedWeekCount }}</h3>
                    <p>Completed This Week</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $completionRate }}%</h3>
                    <p>Completion Rate</p>
                </div>
            </div>
        </div>

        <!-- Create Report Form -->
        <div class="card" id="create-report-form" style="margin-bottom: 30px;">
            <div class="card-header">
                <h3>Create Daily Report</h3>
            </div>
            <form action="{{ route('caregiver.reports.store') }}" method="POST" style="display: grid; gap: 20px;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Select Child</label>
                        <select name="child_id" required style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            <option value="">Select a child</option>
                            @foreach($assignedChildren as $child)
                                <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Date</label>
                        <input type="date" name="report_date" value="{{ date('Y-m-d') }}"
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Mood</label>
                    <input type="hidden" name="mood" id="selectedMood">
                    <div style="display: flex; gap: 10px;">
                        <button type="button" onclick="selectMood('Happy', this)"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😄</button>
                        <button type="button" onclick="selectMood('Content', this)"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😊</button>
                        <button type="button" onclick="selectMood('Fussy', this)"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😐</button>
                        <button type="button" onclick="selectMood('Sad', this)"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😢</button>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Meals</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Breakfast</label>
                            <select name="meals[breakfast]"
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Lunch</label>
                            <select name="meals[lunch]"
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Snack</label>
                            <select name="meals[snack]"
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Nap Time</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Duration (minutes)</label>
                            <input type="number" name="nap_duration" placeholder="90"
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Quality</label>
                            <select name="nap_quality"
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Activities Participated</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" name="activities[]" value="Art & Crafts"> Art & Crafts
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" name="activities[]" value="Outdoor Play"> Outdoor Play
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" name="activities[]" value="Music & Dance"> Music & Dance
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" name="activities[]" value="Story Time"> Story Time
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" name="activities[]" value="Science"> Science
                        </label>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Notes & Observations</label>
                    <textarea name="notes" rows="4" placeholder="Enter any observations, achievements, or concerns..."
                        style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="submit"
                        style="padding: 12px 24px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Reports -->
        <div class="card">
            <div class="card-header">
                <h3>Recent Reports</h3>
            </div>
            <div style="display: grid; gap: 15px;">
                @forelse($recentReports as $report)
                    <div style="padding: 15px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #059669;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <div>
                                <h4 style="color: #1f2937; font-size: 16px; margin-bottom: 5px;">{{ $report->child->first_name }} {{ $report->child->last_name }} - {{ $report->report_date->format('F d, Y') }}</h4>
                                <p style="color: #6b7280; font-size: 13px;">
                                    Mood: {{ $report->mood }} • 
                                    Meals: B:{{ $report->meals['breakfast'] ?? '-' }}/L:{{ $report->meals['lunch'] ?? '-' }}/S:{{ $report->meals['snack'] ?? '-' }} • 
                                    Nap: {{ $report->nap_duration }} mins ({{ $report->nap_quality }})
                                </p>
                            </div>
                            <span style="padding: 6px 12px; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Submitted</span>
                        </div>
                        <p style="color: #4b5563; font-size: 14px;">{{ $report->notes }}</p>
                        @if(!empty($report->activities))
                            <div style="margin-top: 5px;">
                                @foreach($report->activities as $activity)
                                    <span style="display: inline-block; background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 12px; color: #374151; margin-right: 5px;">{{ $activity }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="padding: 20px; text-align: center; color: #6b7280;">No reports filed recently.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function selectMood(mood, btn) {
            document.getElementById('selectedMood').value = mood;
            
            // Reset all buttons
            btn.parentElement.querySelectorAll('button').forEach(b => {
                b.style.borderColor = '#e5e7eb';
                b.style.background = 'white';
            });
            
            // Highlight selected
            btn.style.borderColor = '#059669';
            btn.style.background = '#d1fae5';
        }
    </script>
@endsection
