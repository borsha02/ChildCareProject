<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/settings.css', 'resources/css/admin/modal.css'])
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
                    <h1>System Settings</h1>
                </div>
            </div>

            <div class="content-area">
                <!-- Tabs Container -->
                <div class="tabs-container">
                    <div class="tabs-header">
                        <button class="tab active" onclick="switchTab('fees')">Fee Configuration</button>
                        <button class="tab" onclick="switchTab('hours')">Operating Hours</button>
                        <button class="tab" onclick="switchTab('classrooms')">Classrooms</button>
                        <button class="tab" onclick="switchTab('general')">General Settings</button>
                    </div>

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                    <!-- Fee Configuration Tab -->
                    <div class="tab-content active" id="fees-tab">
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label for="weekly_fee">Weekly Fee</label>
                                <input type="number" id="weekly_fee" name="weekly_fee" value="{{ $settings['fees']['weekly_fee'] ?? 0 }}" placeholder="Enter amount" min="0" step="0.01">
                                <span class="setting-description">Standard weekly fee per child</span>
                            </div>
                            <div class="setting-group">
                                <label for="monthly_fee">Monthly Fee</label>
                                <input type="number" id="monthly_fee" name="monthly_fee" value="{{ $settings['fees']['monthly_fee'] ?? 0 }}" placeholder="Enter amount" min="0" step="0.01">
                                <span class="setting-description">Standard monthly fee per child</span>
                            </div>
                            <div class="setting-group">
                                <label for="sibling_discount">Sibling Discount (%)</label>
                                <input type="number" id="sibling_discount" name="sibling_discount" value="{{ $settings['fees']['sibling_discount'] ?? 0 }}" placeholder="Enter percentage" min="0" max="100" step="0.1">
                                <span class="setting-description">Discount for second child onwards</span>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours Tab -->
                    <div class="tab-content" id="hours-tab">
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label for="opening_time">Opening Time</label>
                                <input type="time" id="opening_time" name="opening_time" value="{{ $settings['timings']['opening_time'] ?? '07:00' }}">
                                <span class="setting-description">Facility opens at</span>
                            </div>
                            <div class="setting-group">
                                <label for="closing_time">Closing Time</label>
                                <input type="time" id="closing_time" name="closing_time" value="{{ $settings['timings']['closing_time'] ?? '18:00' }}">
                                <span class="setting-description">Facility closes at</span>
                            </div>
                            <div class="setting-group">
                                <label for="breakfast_time">Breakfast Time</label>
                                <input type="time" id="breakfast_time" name="breakfast_time" value="{{ $settings['timings']['breakfast_time'] ?? '08:00' }}">
                                <span class="setting-description">Breakfast served at</span>
                            </div>
                            <div class="setting-group">
                                <label for="lunch_time">Lunch Time</label>
                                <input type="time" id="lunch_time" name="lunch_time" value="{{ $settings['timings']['lunch_time'] ?? '12:00' }}">
                                <span class="setting-description">Lunch served at</span>
                            </div>
                            <div class="setting-group">
                                <label for="snack_time">Snack Time</label>
                                <input type="time" id="snack_time" name="snack_time" value="{{ $settings['timings']['snack_time'] ?? '15:00' }}">
                                <span class="setting-description">Afternoon snack at</span>
                            </div>
                            <div class="setting-group">
                                <label for="nap_time">Nap Time</label>
                                <input type="time" id="nap_time" name="nap_time" value="{{ $settings['timings']['nap_time'] ?? '13:00' }}">
                                <span class="setting-description">Nap period starts at</span>
                            </div>
                        </div>
                    </div>
                    </form>

                    <!-- Classrooms Tab -->
                    <div class="tab-content" id="classrooms-tab">
                        <div class="classroom-list">
                            @forelse($settings['classrooms'] as $classroom)
                            <div class="classroom-item">
                                <div class="classroom-info">
                                    <h4>{{ $classroom->name }}</h4>
                                    <p>{{ $classroom->class }} • Capacity: {{ $classroom->capacity }} children • Teacher: {{ $classroom->teacher_name ?? 'Not assigned' }}</p>
                                </div>
                                <div class="classroom-actions">
                                    <button type="button" class="icon-btn edit" onclick='editClassroom(@json($classroom))'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.classrooms.delete', $classroom->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <p style="text-align: center; padding: 20px; color: #6b7280;">No classrooms yet.</p>
                            @endforelse
                        </div>
                        <button type="button" class="add-btn" style="margin-top: 20px;" onclick="openClassroomModal()">
                            <i class="fas fa-plus"></i>
                            Add New Classroom
                        </button>
                    </div>

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                    <!-- General Settings Tab -->
                    <div class="tab-content" id="general-tab">
                        <div class="settings-grid">
                            <div class="setting-group">
                                <label for="system_name">System Name</label>
                                <input type="text" id="system_name" name="system_name" value="{{ $settings['general']['system_name'] ?? 'Childcare Management System' }}">
                                <span class="setting-description">Application name</span>
                            </div>
                            <div class="setting-group">
                                <label for="contact_email">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" value="{{ $settings['general']['contact_email'] ?? '' }}">
                                <span class="setting-description">Primary contact email</span>
                            </div>
                            <div class="setting-group">
                                <label for="contact_phone">Contact Phone</label>
                                <input type="tel" id="contact_phone" name="contact_phone" value="{{ $settings['general']['contact_phone'] ?? '' }}">
                                <span class="setting-description">Primary contact phone</span>
                            </div>
                            <div class="setting-group full-width">
                                <label for="address">Facility Address</label>
                                <textarea id="address" name="address" rows="3">{{ $settings['general']['address'] ?? '' }}</textarea>
                                <span class="setting-description">Physical address</span>
                            </div>
                            <div class="setting-group">
                                <label for="max_capacity">Maximum Capacity</label>
                                <input type="number" id="max_capacity" name="max_capacity" value="{{ $settings['general']['max_capacity'] ?? '' }}">
                                <span class="setting-description">Total children capacity</span>
                            </div>
                        </div>
                    </div>

                    <!-- Save Section -->
                    <div class="save-section">

                        <button type="submit" class="save-btn">
                            <i class="fas fa-save"></i>
                            Save All Changes
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Classroom Modal -->
    <div id="classroomModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closeClassroomModal()">&times;</span>
            <h2 id="classroomModalTitle">Add New Classroom</h2>
            <form id="classroomForm" action="{{ route('admin.classrooms.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="form-group">
                    <label for="classroom_name">Classroom Name</label>
                    <input type="text" name="name" id="classroom_name" required placeholder="e.g., Toddler A">
                </div>
                <div class="form-group">
                    <label for="classroom_class">Class/Age Range</label>
                    <select name="class" id="classroom_class" required>
                        <option value="">Select Class</option>
                        @foreach($available_classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="classroom_capacity">Capacity</label>
                    <input type="number" name="capacity" id="classroom_capacity" required min="1" placeholder="e.g., 10">
                </div>
                <div class="form-group">
                    <label for="classroom_teacher">Teacher Name</label>
                    <select name="teacher_name" id="classroom_teacher">
                        <option value="">Select Caregiver</option>
                        @foreach($caregivers as $caregiver)
                            <option value="{{ $caregiver->name }}">{{ $caregiver->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="closeClassroomModal()">Cancel</button>
                    <button type="submit" class="save-btn" id="classroomSubmitBtn">Add Classroom</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');

            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        function saveSettings() {
            if (confirm('Save all settings changes?')) {
                alert('Settings saved successfully!');
                console.log('Saving settings...');
                // In real app, send to backend
            }
        }

        function openClassroomModal() {
            document.getElementById('classroomModal').style.display = 'block';
            document.getElementById('classroomModalTitle').innerText = 'Add New Classroom';
            document.getElementById('classroomForm').action = "{{ route('admin.classrooms.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('classroomSubmitBtn').innerText = 'Add Classroom';
            document.getElementById('classroomForm').reset();
        }

        function editClassroom(classroom) {
            document.getElementById('classroomModal').style.display = 'block';
            document.getElementById('classroomModalTitle').innerText = 'Edit Classroom';
            document.getElementById('classroomForm').action = `/admin/classrooms/${classroom.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('classroomSubmitBtn').innerText = 'Update Classroom';
            
            document.getElementById('classroom_name').value = classroom.name;
            document.getElementById('classroom_class').value = classroom.class;
            document.getElementById('classroom_capacity').value = classroom.capacity;
            document.getElementById('classroom_teacher').value = classroom.teacher_name || '';
        }

        function closeClassroomModal() {
            document.getElementById('classroomModal').style.display = 'none';
            document.getElementById('classroomForm').reset();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('classroomModal');
            if (event.target == modal) {
                closeClassroomModal();
            }
        }

        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
