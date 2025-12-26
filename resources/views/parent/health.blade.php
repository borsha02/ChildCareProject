<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/health.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-avatar">JD</div>
                    <div class="user-details">
                        <h4>John Doe</h4>
                        <p>Parent Account</p>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('parent.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('parent.child-profile') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Profile</span>
                    </a>
                    <a href="{{ route('parent.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('parent.reports') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('parent.messages') }}" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <span class="badge">3</span>
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">5</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <a href="{{ route('parent.health') }}" class="nav-item active">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="nav-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Assigned Caregivers</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="{{ route('parent.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('parent.help') }}" class="nav-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                    <a href="{{ route('logout') }}" class="nav-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('parent.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Health Records</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search records...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <button class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            <div class="content-area">
                <div class="health-container">
                    <!-- Child Selector -->
                    <div class="child-selector-section">
                        <div class="child-tab active" data-child="emma">
                            <div class="child-avatar">EM</div>
                            <div class="child-info">
                                <h4>Emma Doe</h4>
                                <p>4 years old</p>
                            </div>
                        </div>
                        <div class="child-tab" data-child="lucas">
                            <div class="child-avatar" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                            <div class="child-info">
                                <h4>Lucas James</h4>
                                <p>3 years old</p>
                            </div>
                        </div>
                    </div>

                    <!-- Health Overview Cards -->
                    <div class="health-stats">
                        <div class="health-card">
                            <div class="health-icon blood">
                                <i class="fas fa-tint"></i>
                            </div>
                            <div class="health-details">
                                <h3>O+</h3>
                                <p>Blood Type</p>
                            </div>
                        </div>
                        <div class="health-card">
                            <div class="health-icon weight">
                                <i class="fas fa-weight"></i>
                            </div>
                            <div class="health-details">
                                <h3>16.5 kg</h3>
                                <p>Weight</p>
                            </div>
                        </div>
                        <div class="health-card">
                            <div class="health-icon height">
                                <i class="fas fa-ruler-vertical"></i>
                            </div>
                            <div class="health-details">
                                <h3>105 cm</h3>
                                <p>Height</p>
                            </div>
                        </div>
                        <div class="health-card">
                            <div class="health-icon checkup">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="health-details">
                                <h3>Dec 10</h3>
                                <p>Last Checkup</p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="content-grid">
                        <!-- Vaccination Records -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-syringe"></i> Vaccination Records</h2>
                                <button class="add-btn">
                                    <i class="fas fa-plus"></i> Add Record
                                </button>
                            </div>
                            <div class="vaccination-list">
                                <div class="vaccination-item completed">
                                    <div class="vaccine-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="vaccine-info">
                                        <h4>MMR Vaccine</h4>
                                        <p>Measles, Mumps, Rubella</p>
                                        <span class="vaccine-date">Administered: March 15, 2024</span>
                                    </div>
                                    <span class="vaccine-status completed">Completed</span>
                                </div>
                                <div class="vaccination-item completed">
                                    <div class="vaccine-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="vaccine-info">
                                        <h4>DTaP Vaccine</h4>
                                        <p>Diphtheria, Tetanus, Pertussis</p>
                                        <span class="vaccine-date">Administered: June 20, 2024</span>
                                    </div>
                                    <span class="vaccine-status completed">Completed</span>
                                </div>
                                <div class="vaccination-item upcoming">
                                    <div class="vaccine-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="vaccine-info">
                                        <h4>Polio Vaccine</h4>
                                        <p>Inactivated Poliovirus</p>
                                        <span class="vaccine-date">Scheduled: January 15, 2026</span>
                                    </div>
                                    <span class="vaccine-status upcoming">Upcoming</span>
                                </div>
                            </div>
                        </div>

                        <!-- Allergies & Medical Conditions -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-allergies"></i> Allergies & Conditions</h2>
                                <button class="add-btn">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                            <div class="medical-info">
                                <div class="info-section">
                                    <h3>Known Allergies</h3>
                                    <div class="allergy-tags">
                                        <span class="allergy-tag">
                                            <i class="fas fa-exclamation-triangle"></i> Peanuts
                                        </span>
                                        <span class="allergy-tag">
                                            <i class="fas fa-exclamation-triangle"></i> Dairy
                                        </span>
                                    </div>
                                </div>
                                <div class="info-section">
                                    <h3>Medical Conditions</h3>
                                    <div class="condition-list">
                                        <div class="condition-item">
                                            <i class="fas fa-lungs"></i>
                                            <div>
                                                <h4>Mild Asthma</h4>
                                                <p>Requires inhaler during physical activities</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-section">
                                    <h3>Emergency Contact</h3>
                                    <div class="emergency-contact">
                                        <i class="fas fa-phone-alt"></i>
                                        <div>
                                            <h4>Dr. Sarah Johnson</h4>
                                            <p>Pediatrician: +1 (555) 123-4567</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medication Records -->
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-pills"></i> Current Medications</h2>
                            <button class="add-btn">
                                <i class="fas fa-plus"></i> Add Medication
                            </button>
                        </div>
                        <table class="medication-table">
                            <thead>
                                <tr>
                                    <th>Medication</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="medication-name">
                                            <i class="fas fa-capsules"></i>
                                            <span>Albuterol Inhaler</span>
                                        </div>
                                    </td>
                                    <td>2 puffs</td>
                                    <td>As needed</td>
                                    <td>Jan 10, 2025</td>
                                    <td>Ongoing</td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="medication-name">
                                            <i class="fas fa-tablets"></i>
                                            <span>Children's Multivitamin</span>
                                        </div>
                                    </td>
                                    <td>1 tablet</td>
                                    <td>Daily</td>
                                    <td>Sep 1, 2025</td>
                                    <td>Ongoing</td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>
                                        <button class="action-icon-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-icon-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Health Checkup History -->
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-notes-medical"></i> Checkup History</h2>
                            <button class="view-all-btn">View All</button>
                        </div>
                        <div class="checkup-timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h4>Annual Physical Examination</h4>
                                        <span class="timeline-date">Dec 10, 2025</span>
                                    </div>
                                    <p class="timeline-doctor">Dr. Sarah Johnson - Pediatrics</p>
                                    <div class="timeline-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Weight:</span>
                                            <span class="detail-value">16.5 kg</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Height:</span>
                                            <span class="detail-value">105 cm</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">BMI:</span>
                                            <span class="detail-value">14.9 (Normal)</span>
                                        </div>
                                    </div>
                                    <p class="timeline-notes">
                                        <strong>Notes:</strong> Child is developing well. All vital signs normal. Continue current diet and exercise routine.
                                    </p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h4>Dental Checkup</h4>
                                        <span class="timeline-date">Sep 15, 2025</span>
                                    </div>
                                    <p class="timeline-doctor">Dr. Michael Chen - Dentistry</p>
                                    <p class="timeline-notes">
                                        <strong>Notes:</strong> Teeth are healthy. No cavities detected. Continue regular brushing routine.
                                    </p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h4>Vision Screening</h4>
                                        <span class="timeline-date">Jun 20, 2025</span>
                                    </div>
                                    <p class="timeline-doctor">Dr. Emily Rodriguez - Ophthalmology</p>
                                    <p class="timeline-notes">
                                        <strong>Notes:</strong> Vision is 20/20. No corrective lenses needed at this time.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Child tab switching
        document.querySelectorAll('.child-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.child-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                // Load specific child's health data
            });
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
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
