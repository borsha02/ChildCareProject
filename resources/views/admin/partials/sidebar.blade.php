<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
            <h2>Admin Panel</h2>
        </div>
        <div class="user-info">
            <div class="user-avatar">AD</div>
            <div class="user-details">
                <h4>Administrator</h4>
                <p>System Admin</p>
            </div>
        </div>
    </div>

    <nav class="nav-menu">
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">User Management</div>
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.children') }}" class="nav-item {{ request()->routeIs('admin.children') ? 'active' : '' }}">
                <i class="fas fa-child"></i>
                <span>Child Records</span>
                @if(($stats['pending_registrations'] ?? 0) > 0)
                    <span class="badge">{{ $stats['pending_registrations'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.staff') }}" class="nav-item {{ request()->routeIs('admin.staff') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span>Staff Management</span>
            </a>
            <a href="{{ route('admin.ratings') }}" class="nav-item {{ request()->routeIs('admin.ratings') ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                <span>Caregiver Ratings</span>
            </a>
            <a href="{{ route('admin.job-applications') }}" class="nav-item {{ request()->routeIs('admin.job-applications') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>
                <span>Job Applications</span>
                @if(isset($pendingJobAppsCount) && $pendingJobAppsCount > 0)
                    <span class="badge" style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; margin-left: auto;">{{ $pendingJobAppsCount }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Operations</div>
            <a href="{{ route('admin.attendance') }}" class="nav-item {{ request()->routeIs('admin.attendance') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Attendance</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Daily Reports</span>
            </a>
            <a href="{{ route('admin.events.index') }}" class="nav-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Events Management</span>
            </a>
            <a href="{{ route('admin.invoices') }}" class="nav-item {{ request()->routeIs('admin.invoices') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Billing & Invoices</span>
            </a>
            <a href="{{ route('admin.payments.pending') }}" class="nav-item {{ request()->routeIs('admin.payments.pending') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span>Payment Approvals</span>
                <span class="badge">{{ $stats['pending_payments'] ?? 0 }}</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Communication</div>
            <a href="{{ route('admin.announcements') }}" class="nav-item {{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            <a href="{{ route('admin.communication') }}" class="nav-item {{ request()->routeIs('admin.communication') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>Communication Logs</span>
            </a> 
        </div>

        <div class="nav-section">
            <div class="nav-section-title">System</div>
            <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a> 
            <a href="{{ route('admin.backup') }}" class="nav-item {{ request()->routeIs('admin.backup') ? 'active' : '' }}">
                <i class="fas fa-database"></i>
                <span>Backup & Restore</span>
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
