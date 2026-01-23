<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/attendance.css', 'resources/css/admin/attendance-list.css'])
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
                    <h1>Attendance Monitoring</h1>
                </div>
                <div class="top-bar-actions">
                    <input type="date" id="attendance_date" class="date-picker" value="{{ $date }}" onchange="window.location.href='{{ route('admin.attendance') }}?date=' + this.value">
                    <button class="export-btn" onclick="window.location.href='{{ route('admin.attendance.export') }}?date={{ $date }}'">
                        <i class="fas fa-download"></i>
                        Export Report
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-card clickable" onclick="showChildrenModal('present')">
                        <div class="stat-label">Present Today</div>
                        <div class="stat-value">{{ $stats['present_today'] }}</div>
                        <div class="stat-percentage">Out of {{ $children->count() }} children</div>
                    </div>
                    <div class="stat-card red clickable" onclick="showChildrenModal('absent')">
                        <div class="stat-label">Absent Today</div>
                        <div class="stat-value">{{ $stats['absent_today'] }}</div>
                        <div class="stat-percentage">{{ $children->count() > 0 ? round(($stats['absent_today'] / $children->count()) * 100) : 0 }}% absence rate</div>
                    </div>
                    <div class="stat-card orange clickable" onclick="showChildrenModal('late')">
                        <div class="stat-label">Late Arrivals</div>
                        <div class="stat-value">{{ $stats['late_today'] }}</div>
                        <div class="stat-percentage">{{ $children->count() > 0 ? round(($stats['late_today'] / $children->count()) * 100) : 0 }}% late rate</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-label">Attendance Rate</div>
                        <div class="stat-value">{{ $stats['attendance_rate'] }}%</div>
                        <div class="stat-percentage">Daily Average</div>
                    </div>
                </div>


                <!-- Children List Section -->
                <div class="children-list-section" id="childrenListSection" style="display: none;">
                    <div class="list-header">
                        <h3 id="listTitle">Children Details</h3>
                        <div class="header-buttons">
                            <button class="download-pdf-btn" id="downloadPdfBtn" style="display: none;" onclick="downloadAbsentPDF()">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                            <button class="close-list-btn" onclick="closeChildrenList()">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                    <div id="childrenListContent"></div>
                </div>

            </div>
            <!-- Toast Container -->
            <div class="toast-container" id="toastContainer"></div>
        </main>
    </div>

    <script>
        // Children data from backend
        @php
            $childrenArray = $children->map(function($child) {
                $record = $child->attendances->first();
                return [
                    'id' => $child->id,
                    'first_name' => $child->first_name,
                    'last_name' => $child->last_name,
                    'class' => $child->class,
                    'status' => $record ? $record->status : 'absent',
                    'check_in_time' => $record && $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') : 'N/A',
                    'check_out_time' => $record && $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') : 'N/A',
                    'notes' => $record ? $record->notes : '',
                    'parent_name' => $child->parent ? $child->parent->name : 'N/A',
                    'parent_phone' => $child->parent ? $child->parent->phone : 'N/A'
                ];
            })->values();
        @endphp
        const childrenData = @json($childrenArray);

        function showChildrenModal(status) {
            const listSection = document.getElementById('childrenListSection');
            const listTitle = document.getElementById('listTitle');
            const childrenListContent = document.getElementById('childrenListContent');
            
            // Filter children by status (case-insensitive)
            const filteredChildren = childrenData.filter(child => {
                return child.status && child.status.toLowerCase() === status.toLowerCase();
            });
            
            // Update title
            const statusTitles = {
                'present': 'Present Children',
                'absent': 'Absent Children',
                'late': 'Late Arrivals'
            };
            listTitle.textContent = statusTitles[status] || 'Children Details';
            
            // Generate HTML for children list
            if (filteredChildren.length === 0) {
                childrenListContent.innerHTML = '<p class="no-data">No children found with this status.</p>';
            } else {
                // Different table headers based on status
                let tableHeaders = '';
                if (status === 'absent') {
                    tableHeaders = '<th>Name</th><th>Class</th><th>Parent Name</th><th>Parent Phone</th>';
                } else {
                    tableHeaders = '<th>Name</th><th>Class</th><th>Check-in</th><th>Check-out</th>';
                }
                
                let html = `<table class="children-table"><thead><tr>${tableHeaders}</tr></thead><tbody>`;
                
                filteredChildren.forEach(child => {
                    if (status === 'absent') {
                        // Absent children - show parent info
                        html += `
                            <tr>
                                <td>
                                    <div class="table-student-info">
                                        <div class="table-student-avatar" style="background: #${Math.floor(Math.random()*16777215).toString(16)};">
                                            ${child.first_name.charAt(0)}${child.last_name.charAt(0)}
                                        </div>
                                        <div>
                                            <div class="table-student-name">${child.first_name} ${child.last_name}</div>
                                            <div class="table-student-id">ID: CH${String(child.id).padStart(3, '0')}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${child.class}</td>
                                <td><strong>${child.parent_name}</strong></td>
                                <td><a href="tel:${child.parent_phone}" class="phone-link">${child.parent_phone}</a></td>
                            </tr>
                        `;
                    } else {
                        // Present/Late children - show check-in/out times
                        html += `
                            <tr>
                                <td>
                                    <div class="table-student-info">
                                        <div class="table-student-avatar" style="background: #${Math.floor(Math.random()*16777215).toString(16)};">
                                            ${child.first_name.charAt(0)}${child.last_name.charAt(0)}
                                        </div>
                                        <div>
                                            <div class="table-student-name">${child.first_name} ${child.last_name}</div>
                                            <div class="table-student-id">ID: CH${String(child.id).padStart(3, '0')}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${child.class}</td>
                                <td>${child.check_in_time}</td>
                                <td>${child.check_out_time}</td>
                            </tr>
                        `;
                    }
                });
                
                html += '</tbody></table>';
                childrenListContent.innerHTML = html;
            }
            
            // Show/hide download button based on status
            const downloadBtn = document.getElementById('downloadPdfBtn');
            if (status === 'absent') {
                downloadBtn.style.display = 'inline-flex';
            } else {
                downloadBtn.style.display = 'none';
            }
            
            // Show list section with smooth scroll
            listSection.style.display = 'block';
            setTimeout(() => {
                listSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        function downloadAbsentPDF() {
            const date = document.getElementById('attendance_date').value;
            window.location.href = '{{ route("admin.attendance.absent-pdf") }}?date=' + date;
        }

        function closeChildrenList() {
            const listSection = document.getElementById('childrenListSection');
            listSection.style.display = 'none';
        }


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
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Ensure correct date based on client timezone
        document.addEventListener('DOMContentLoaded', () => {
             const dateInput = document.getElementById('attendance_date');
             if (!dateInput) return;
 
             const urlParams = new URLSearchParams(window.location.search);
             const hasDateParam = urlParams.has('date');
 
             if (!hasDateParam) {
                 const serverDate = dateInput.value;
                 const clientDate = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
 
                 if (serverDate !== clientDate) {
                     // Redirect to client date to load correct data
                     window.location.search = `?date=${clientDate}`;
                 }
             }
        });
    </script>
</body>

</html>
