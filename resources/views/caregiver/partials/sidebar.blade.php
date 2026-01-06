<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-baby"></i>
            <h2>Childcare</h2>
        </div>
        <div class="user-info">
            <div class="user-avatar">{{ substr(Auth::user()->name ?? 'User', 0, 2) }}</div>
            <div class="user-details">
                <h4>{{ Auth::user()->name }}</h4>
                <p>Caregiver</p>
            </div>
        </div>
    </div>

    <nav class="nav-menu">
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            <a href="{{ route('caregiver.dashboard') }}" class="nav-item {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('caregiver.assigned') }}" class="nav-item {{ request()->routeIs('caregiver.assigned') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Assigned Children</span>
            </a>
            <a href="{{ route('caregiver.schedule') }}" class="nav-item {{ request()->routeIs('caregiver.schedule') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>My Schedule</span>
            </a>
            <a href="{{ route('caregiver.attendance') }}" class="nav-item {{ request()->routeIs('caregiver.attendance') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Attendance</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Activities</div>
            <a href="{{ route('caregiver.daily-reports') }}" class="nav-item {{ request()->routeIs('caregiver.daily-reports') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Daily Reports</span>
            </a>
            <a href="{{ route('caregiver.health') }}" class="nav-item {{ request()->routeIs('caregiver.health') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>
                <span>Health Records</span>
            </a>
            <a href="{{ route('caregiver.events') }}" class="nav-item {{ request()->routeIs('caregiver.events') ? 'active' : '' }}">
                <i class="fas fa-calendar-days"></i>
                <span>Events</span>
            </a>
            <a href="{{ route('caregiver.messages') }}" class="nav-item {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>Messages</span>
                @php
                    $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                @if($unreadMessages > 0)
                    <span class="badge">{{ $unreadMessages }}</span>
                @endif
            </a>
            <a href="{{ route('caregiver.notifications') }}" class="nav-item {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
                @php
                    $unreadNotifications = Auth::user()->unreadNotifications->count();
                @endphp
                @if($unreadNotifications > 0)
                    <span class="badge">{{ $unreadNotifications }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Personal</div>
            <a href="{{ route('caregiver.leave') }}" class="nav-item {{ request()->routeIs('caregiver.leave') ? 'active' : '' }}">
                <i class="fas fa-calendar-times"></i>
                <span>Leave Requests</span>
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
