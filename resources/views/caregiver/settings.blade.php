@extends('layouts.caregiver')

@section('title', 'Settings')

@section('styles')
    @vite(['resources/css/caregiver/settings.css'])
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
            <h1>Settings</h1>
        </div>
        <div class="top-bar-actions">
            <!-- Reuse notification partial or similar pattern if available -->
            <a href="{{ route('caregiver.notifications') }}" class="icon-btn">
                <i class="fas fa-bell"></i>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="notification-dot"></span>
                @endif
            </a>
            <a href="{{ route('caregiver.messages') }}" class="icon-btn">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        <div class="settings-container">
            <!-- Settings Navigation -->
            <div class="settings-nav">
                <button class="settings-nav-item active" data-tab="profile">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </button>
                <button class="settings-nav-item" data-tab="security">
                    <i class="fas fa-lock"></i>
                    <span>Security</span>
                </button>
                <button class="settings-nav-item" data-tab="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </button>
                <button class="settings-nav-item" data-tab="privacy">
                    <i class="fas fa-shield-alt"></i>
                    <span>Privacy</span>
                </button>
            </div>

            <!-- Settings Content -->
            <div class="settings-content">
                <!-- Profile Tab -->
                <div class="settings-tab active" id="profile-tab">
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-user-circle"></i> Profile Information</h2>
                        </div>
                        <div class="profile-section">
                            <div class="profile-avatar-section">
                                <div class="profile-avatar-large">
                                    {{ substr(Auth::user()->name ?? 'User', 0, 1) }}
                                </div>
                                <div class="profile-header-info">
                                    <h3>{{ Auth::user()->name }}</h3>
                                    <p>Caregiver Account</p>
                                    <p>{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <form class="settings-form" action="{{ route('caregiver.settings.update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" value="{{ Auth::user()->email }}" class="form-input" disabled style="background-color: #f3f4f6; cursor: not-allowed;">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="tel" name="phone" value="{{ Auth::user()->phone }}" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="date" name="dob" value="{{ Auth::user()->dob }}" class="form-input">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" value="{{ Auth::user()->address }}" class="form-input">
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn-cancel">Cancel</button>
                                    <button type="submit" class="btn-save">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="settings-tab" id="security-tab">
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-key"></i> Change Password</h2>
                        </div>
                        <form class="settings-form" action="{{ route('caregiver.settings.password') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-input @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                                @error('current_password')
                                    <span class="text-danger" style="color: #ef4444; font-size: 0.875em;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-input @error('new_password') is-invalid @enderror" placeholder="Enter new password" required>
                                @error('new_password')
                                    <span class="text-danger" style="color: #ef4444; font-size: 0.875em;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-input" placeholder="Confirm new password" required>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-save">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div class="settings-tab" id="notifications-tab">
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-envelope"></i> Email Notifications</h2>
                        </div>
                        <div class="setting-item">
                            <div class="setting-info">
                                <h4>New Message Alerts</h4>
                                <p>Receive email notifications for new messages from parents</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-info">
                                <h4>Event Reminders</h4>
                                <p>Get notified about upcoming events</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-info">
                                <h4>Schedule Updates</h4>
                                <p>Receive notifications for schedule changes</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Tab -->
                <div class="settings-tab" id="privacy-tab">
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-eye"></i> Profile Visibility</h2>
                        </div>
                        <div class="setting-item">
                            <div class="setting-info">
                                <h4>Show Contact Info to Parents</h4>
                                <p>Allow assigned parents to see your phone number</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-database"></i> Data & Privacy</h2>
                        </div>
                        <div class="privacy-actions">
                            <button class="privacy-btn">
                                <i class="fas fa-download"></i>
                                <div>
                                    <h4>Download Your Data</h4>
                                    <p>Get a copy of your information</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast Notification Feature
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                `;
                document.body.appendChild(toast);
                
                // Trigger reflow
                toast.offsetHeight;
                
                // Show toast
                toast.classList.add('show');
                
                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            }

            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif

            // Settings Tab Switching
            const navItems = document.querySelectorAll('.settings-nav-item');
            const tabs = document.querySelectorAll('.settings-tab');

            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    const tabId = this.dataset.tab;

                    // Update Active State
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');

                    // Show Content
                    tabs.forEach(tab => tab.classList.remove('active'));
                    document.getElementById(tabId + '-tab').classList.add('active');
                });
            });

            // Cancel Button Logic
            document.querySelectorAll('.btn-cancel').forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('form');
                    if (form) {
                        form.reset();
                        showToast('Changes discarded', 'info');
                    }
                });
            });
        });
    </script>
@endsection
