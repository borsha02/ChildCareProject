@extends('layouts.caregiver')

@section('title', 'Daily Reports')

@section('styles')
    @vite(['resources/css/caregiver/daily-reports.css'])
@endsection

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <div style="display: flex; align-items: center;">
            <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Daily Reports</h1>
        </div>
        <div class="top-bar-actions">
            <button onclick="document.getElementById('create-report-form').scrollIntoView({behavior: 'smooth'})"
                class="btn-create">
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
            <div class="alert-success">
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
        <div class="card create-report-section" id="create-report-form">
            <div class="card-header">
                <h3>Create Daily Report</h3>
            </div>
            <form action="{{ route('caregiver.reports.store') }}" method="POST" class="form-grid">
                @csrf
                <div class="form-row">
                    <div>
                        <label class="form-label">Select Child</label>
                        <select name="child_id" required class="form-control">
                            <option value="">Select a child</option>
                            @foreach($assignedChildren as $child)
                                <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Date</label>
                        <input type="date" name="report_date" value="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                </div>

                <div>
                    <label class="form-label">Mood</label>
                    <input type="hidden" name="mood" id="selectedMood">
                    <div class="mood-buttons">
                        <button type="button" onclick="selectMood('Happy', this)" class="mood-btn">😄</button>
                        <button type="button" onclick="selectMood('Content', this)" class="mood-btn">😊</button>
                        <button type="button" onclick="selectMood('Fussy', this)" class="mood-btn">😐</button>
                        <button type="button" onclick="selectMood('Sad', this)" class="mood-btn">😢</button>
                    </div>
                </div>

                <div>
                    <label class="form-label">Meals</label>
                    <div class="meals-grid">
                        <div>
                            <label class="sub-label">Breakfast</label>
                            <select name="meals[breakfast]" class="meal-select">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="sub-label">Lunch</label>
                            <select name="meals[lunch]" class="meal-select">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="sub-label">Snack</label>
                            <select name="meals[snack]" class="meal-select">
                                <option value="All">All</option>
                                <option value="Most">Most</option>
                                <option value="Some">Some</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label">Nap Time</label>
                    <div class="nap-grid">
                        <div>
                            <label class="sub-label">Duration (minutes)</label>
                            <input type="number" name="nap_duration" placeholder="90" class="meal-select">
                        </div>
                        <div>
                            <label class="sub-label">Quality</label>
                            <select name="nap_quality" class="meal-select">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label">Activities Participated</label>
                    <div class="activities-container">
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Art & Crafts"> Art & Crafts
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Outdoor Play"> Outdoor Play
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Music & Dance"> Music & Dance
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Story Time"> Story Time
                        </label>
                        <label class="activity-label">
                            <input type="checkbox" name="activities[]" value="Science"> Science
                        </label>
                    </div>
                </div>

                <div>
                    <label class="form-label">Notes & Observations</label>
                    <textarea name="notes" rows="4" placeholder="Enter any observations, achievements, or concerns..." class="notes-area"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
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
            <div class="reports-list">
                @forelse($recentReports as $report)
                    <div class="report-item">
                        <div class="report-header">
                            <div>
                                <h4 class="report-title">{{ $report->child->first_name }} {{ $report->child->last_name }} - {{ $report->report_date->format('F d, Y') }}</h4>
                                <p class="report-meta">
                                    Mood: {{ $report->mood }} • 
                                    Meals: B:{{ $report->meals['breakfast'] ?? '-' }}/L:{{ $report->meals['lunch'] ?? '-' }}/S:{{ $report->meals['snack'] ?? '-' }} • 
                                    Nap: {{ $report->nap_duration }} mins ({{ $report->nap_quality }})
                                </p>
                            </div>
                            <span class="status-badge">Submitted</span>
                        </div>
                        <p class="report-notes">{{ $report->notes }}</p>
                        @if(!empty($report->activities))
                            <div class="activity-tags">
                                @foreach($report->activities as $activity)
                                    <span class="activity-tag">{{ $activity }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-reports">No reports filed recently.</div>
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
