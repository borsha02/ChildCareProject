<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records - Caregiver Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/caregiver/dashboard.css'])
</head>
<body>
    <div class="dashboard-container">
        @include('caregiver.partials.sidebar')

        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Health Records</h1>
                <div class="top-bar-actions">
                    <button style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        <i class="fas fa-plus"></i> Add Health Entry
                    </button>
                </div>
            </div>

            <div class="content-area">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="stat-details"><h3>3</h3><p>Allergies to Monitor</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="fas fa-pills"></i></div>
                        <div class="stat-details"><h3>2</h3><p>Medications Today</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-thermometer-half"></i></div>
                        <div class="stat-details"><h3>1</h3><p>Temperature Checks</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-band-aid"></i></div>
                        <div class="stat-details"><h3>0</h3><p>Incidents Today</p></div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h3>Children Health Overview</h3>
                    </div>
                    <div class="children-list">
                        <div class="child-item">
                            <div class="child-avatar">EM</div>
                            <div class="child-info">
                                <h4>Emma Martinez</h4>
                                <p>Allergies: Peanuts, Dairy • No current medications</p>
                            </div>
                        </div>
                        <div class="child-item">
                            <div class="child-avatar" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                            <div class="child-info">
                                <h4>Lucas Johnson</h4>
                                <p>Allergies: None • Medication: Asthma inhaler (as needed)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/caregiver.js') }}"></script>
</body>
</html>
