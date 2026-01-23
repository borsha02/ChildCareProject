<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Caregivers - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/parentdashboard.css', 'resources/css/caregivers.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Little Stars Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <h4>{{ Auth::user()->name }}</h4>
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
                        @php
                            $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                    </a>
                    <a href="{{ route('parent.events') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <a href="{{ route('parent.health') }}" class="nav-item">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('parent.invoice') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('parent.caregivers') }}" class="nav-item active">
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
                    <h1>Assigned Caregivers</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search caregivers...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <div class="caregiver-grid">
                    @forelse($caregivers as $caregiver)
                        <div class="caregiver-card">
                            <div class="caregiver-avatar" style="background: linear-gradient(135deg, {{ $loop->iteration % 2 == 0 ? '#6366f1, #8b5cf6' : '#ec4899, #f43f5e' }});">
                                {{ strtoupper(substr($caregiver->name, 0, 1) . substr(strrchr($caregiver->name, ' '), 1, 1)) }}
                            </div>
                            <div class="caregiver-info">
                                <h3>{{ $caregiver->name }}</h3>
                                <p class="role">Caregiver</p>
                                <p class="contact"><i class="far fa-envelope"></i> {{ $caregiver->email }}</p> 
                            </div>
                            <div class="caregiver-actions">
                                @if(session('success') && $caregivers->where('id', session('rated_caregiver_id'))->first()?->id == $caregiver->id)
                                    <div class="alert alert-success" style="position: absolute; top: 10px; left: 50%; transform: translateX(-50%); background: #d1fae5; color: #065f46; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1); white-space: nowrap; z-index: 10;">
                                        <i class="fas fa-check-circle"></i> Rating Submitted!
                                    </div>
                                @endif

                                <a href="{{ route('parent.messages', ['caregiver_id' => $caregiver->id]) }}" class="btn-message btn-primary-action">
                                    <i class="fas fa-comment-dots"></i> Send Message
                                </a>
                                
                                @if($caregiver->is_rated)
                                    <button class="btn-message" style="background: #e2e8f0; color: #64748b; cursor: default;">
                                        <i class="fas fa-check"></i> Rated
                                    </button>
                                @else
                                    <button onclick="openRatingModal({{ $caregiver->id }}, '{{ $caregiver->name }}')" class="btn-message btn-secondary-action">
                                        <i class="fas fa-star"></i> Rate Caregiver
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-caregivers" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                            <i class="fas fa-chalkboard-teacher" style="font-size: 48px; color: #cbd5e1; margin-bottom: 20px;"></i>
                            <h3 style="color: #64748b;">No Caregivers Assigned Yet</h3>
                            <p style="color: #94a3b8;">Once your child is enrolled in a class, their teacher will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 id="ratingModalTitle">Rate Caregiver</h2>
                <span onclick="closeRatingModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            </div>
            <form action="{{ route('parent.ratings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="caregiver_id" id="ratingCaregiverId">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Rating:</label>
                    <div class="rating-stars" style="display: flex; gap: 10px; font-size: 24px; cursor: pointer;">
                        <i class="far fa-star star" data-value="1" onclick="setRating(1)"></i>
                        <i class="far fa-star star" data-value="2" onclick="setRating(2)"></i>
                        <i class="far fa-star star" data-value="3" onclick="setRating(3)"></i>
                        <i class="far fa-star star" data-value="4" onclick="setRating(4)"></i>
                        <i class="far fa-star star" data-value="5" onclick="setRating(5)"></i>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" required>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="comment" style="display: block; margin-bottom: 5px;">Comment (Optional):</label>
                    <textarea name="comment" id="comment" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>

                <div class="form-actions" style="text-align: right;">
                    <button type="button" onclick="closeRatingModal()" style="padding: 8px 16px; margin-right: 10px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRatingModal(caregiverId, caregiverName) {
            document.getElementById('ratingModal').style.display = 'block';
            document.getElementById('ratingCaregiverId').value = caregiverId;
            document.getElementById('ratingModalTitle').innerText = 'Rate ' + caregiverName;
            setRating(0); // Reset stars
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').style.display = 'none';
        }

        function setRating(value) {
            document.getElementById('ratingValue').value = value;
            const stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= value) {
                    star.classList.remove('far'); // Empty star
                    star.classList.add('fas'); // Filled star
                    star.style.color = '#f59e0b';
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    star.style.color = 'inherit';
                }
            });
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('ratingModal')) {
                closeRatingModal();
            }
        }
    </script>
