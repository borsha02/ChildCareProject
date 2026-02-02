<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/analytics.css'])
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Analytics Dashboard</h1>
                </div>
                <div class="top-bar-actions" style="display: flex; gap: 10px;">
                    <button class="btn-primary" onclick="openReportModal()" style="padding: 8px 15px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-file-alt"></i> Generate Report
                    </button>
                    <select class="filter-select" onchange="window.location.href = '?period=' + this.value">
                        <option value="7_days" {{ ($analytics['period'] ?? '') == '7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30_days" {{ ($analytics['period'] ?? '') == '30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="3_months" {{ ($analytics['period'] ?? '') == '3_months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="1_year" {{ ($analytics['period'] ?? '') == '1_year' ? 'selected' : '' }}>Last Year</option>
                    </select>
                </div>
            </div>

            <div class="content-area">
                <!-- Key Metrics Row -->
                <div class="metrics-row">
                    <div class="metric-card">
                        <div class="metric-label">Total Revenue</div>
                        <div class="metric-value">{{ number_format($analytics['payments']['total_life'] ?? 0, 2) }}</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            
                        </div>
                    </div>
                    <div class="metric-card green">
                        <div class="metric-label">Active Children</div>
                        <div class="metric-value">{{ $analytics['enrollment']['active'] ?? 0 }}</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            
                        </div>
                    </div>
                    <div class="metric-card orange">
                        <div class="metric-label">Avg Attendance</div>
                        <div class="metric-value">{{ $analytics['attendance']['average'] ?? 0 }}%</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            
                        </div>
                    </div>
                    <div class="metric-card purple">
                        <div class="metric-label">Parent Satisfaction</div>
                        <div class="metric-value">{{ $analytics['feedback']['positive'] ?? 0 }}%</div>
                        <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="analytics-grid">
                    <!-- Attendance Chart -->
                    <div class="analytics-card">
                        <div class="card-header">
                            <h3>Weekly Attendance</h3>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                @php
                                    $currentOffset = $analytics['week_offset'] ?? 0;
                                    $period = $analytics['period'] ?? '7_days';
                                @endphp
                                <a href="?period={{ $period }}&week_offset={{ $currentOffset + 1 }}" style="text-decoration: none; color: #6b7280; font-size: 14px; padding: 2px 8px; border: 1px solid #ddd; border-radius: 4px;" title="Previous Week">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <span style="font-size: 13px; color: #666; font-weight: 500;">
                                    @if($currentOffset == 0) Current Week @else {{ abs($currentOffset) }} Weeks Ago @endif
                                </span>
                                <a href="?period={{ $period }}&week_offset={{ $currentOffset - 1 }}" style="text-decoration: none; color: #6b7280; font-size: 14px; padding: 2px 8px; border: 1px solid #ddd; border-radius: 4px; pointer-events: {{ $currentOffset <= 0 ? 'none' : 'auto' }}; opacity: {{ $currentOffset <= 0 ? '0.5' : '1' }};" title="Next Week">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="bar-chart">
                                @foreach($analytics['attendance']['labels'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $index => $day)
                                <div class="bar-item">
                                    <div class="bar" style="height: {{ ($analytics['attendance']['data'][$index] ?? 0) }}%;">
                                        <span class="bar-value">{{ $analytics['attendance']['data'][$index] ?? 0 }}%</span>
                                    </div>
                                    <div class="bar-label">{{ $day }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Distribution -->
                    @php
                        $pos = $analytics['feedback']['positive'] ?? 0;
                        $neu = $analytics['feedback']['neutral'] ?? 0;
                        $neg = $analytics['feedback']['negative'] ?? 0;
                        
                        // Calculate degrees
                        $degPos = ($pos / 100) * 360;
                        $degNeu = ($neu / 100) * 360;
                        $degNeg = ($neg / 100) * 360; // Remainder
                        
                        // Conic gradient stops
                        $stop1 = $degPos;
                        $stop2 = $degPos + $degNeu;
                    @endphp
                    <div class="analytics-card">
                        <div class="card-header">
                            <h3>Parent Feedback</h3>
                            <div class="card-icon green">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="pie-chart-container">
                                <div class="pie-chart" style="background: conic-gradient(#10b981 0deg {{ $stop1 }}deg, #fbbf24 {{ $stop1 }}deg {{ $stop2 }}deg, #ef4444 {{ $stop2 }}deg 360deg);">
                                    <div class="pie-center">
                                        <h4>{{ $pos }}%</h4>
                                        <p>Positive</p>
                                    </div>
                                </div>
                                <div class="pie-legend">
                                    <div class="legend-item">
                                        <div class="legend-color green"></div>
                                        <div class="legend-text">
                                            <h5>Positive</h5>
                                            <p>Very satisfied</p>
                                        </div>
                                        <div class="legend-value">{{ $pos }}%</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color yellow"></div>
                                        <div class="legend-text">
                                            <h5>Neutral</h5>
                                            <p>Satisfied</p>
                                        </div>
                                        <div class="legend-value">{{ $neu }}%</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color red"></div>
                                        <div class="legend-text">
                                            <h5>Negative</h5>
                                            <p>Needs improvement</p>
                                        </div>
                                        <div class="legend-value">{{ $neg }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                @php
                    $maxRevenue = max($analytics['payments']['data'] ?? [0]) ?: 1;
                @endphp
               <!-- <div class="analytics-card">
                    <div class="card-header">
                        <h3>Monthly Revenue Trend</h3>
                        <div class="card-icon purple">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart">
                            @foreach($analytics['payments']['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] as $index => $month)
                            @php
                                $val = $analytics['payments']['data'][$index] ?? 0;
                                $height = ($val / $maxRevenue) * 100;
                            @endphp
                            <div class="bar-item">
                                <div class="bar" style="height: {{ $height }}%; background: linear-gradient(180deg, #8b5cf6, #7c3aed);">
                                    <span class="bar-value">{{ number_format($val) }}</span>
                                </div>
                                <div class="bar-label">{{ $month }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div> -->
            </div>
        </main>
            </div>
        </main>
    </div>

    <!-- Report Generation Modal -->
    <div id="reportModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div class="modal-content" style="background: white; padding: 25px; border-radius: 10px; width: 400px; max-width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 20px;">Generate Custom Report</h2>
                <button onclick="closeReportModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #666;">&times;</button>
            </div>
            
            <form action="{{ route('admin.analytics.report') }}" method="POST" target="_blank">
                @csrf
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Start Date</label>
                    <input type="date" name="start_date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">End Date</label>
                    <input type="date" name="end_date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                
                <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeReportModal()" style="padding: 10px 20px; background: #e5e7eb; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-print"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        const reportModal = document.getElementById('reportModal');
        
        function openReportModal() {
            reportModal.style.display = 'flex';
        }
        
        function closeReportModal() {
            reportModal.style.display = 'none';
        }
        
        // Close outside click
        reportModal.addEventListener('click', (e) => {
            if(e.target === reportModal) {
                closeReportModal();
            }
        });

        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target) && e.target !== reportModal && !reportModal.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>

</html>
