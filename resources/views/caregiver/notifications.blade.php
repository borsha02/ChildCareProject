@extends('layouts.caregiver')

@section('title', 'Notifications')

@section('styles')
    @vite(['resources/css/caregiver/notifications.css'])
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
            <h1>Notifications</h1>
        </div>
        <div class="top-bar-actions">
            <div class="search-box">
                <input type="text" placeholder="Search notifications...">
                <i class="fas fa-search"></i>
            </div>
            <a href="{{ route('caregiver.notifications') }}" class="icon-btn active">
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
        <div class="notifications-container">
            <div class="notifications-header">
                <div class="header-left">
                    <h2>All Notifications</h2>
                    <span class="badge">{{ $unreadCount }} New</span>
                </div>
                <div class="header-actions">
                    <form action="{{ route('caregiver.notifications.mark-all') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="mark-read-btn">Mark all as read</button>
                    </form>
                    <select class="filter-select" onchange="filterNotifications(this.value)">
                        <option value="all">All</option>
                        <option value="unread">Unread</option>
                    </select>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-success" style="margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="notification-list">
                @forelse($notifications as $notification)
                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                        <div class="notification-icon {{ $notification->data['color'] ?? 'info' }}">
                            <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-top">
                                <h4>{{ $notification->data['title'] }}</h4>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                                    <div class="notification-actions">
                                        @if(!$notification->read_at)
                                            <form action="{{ route('caregiver.notifications.mark-read', $notification->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn-small" title="Mark as read">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('caregiver.notifications.delete', $notification->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-small delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <p>{{ $notification->data['message'] }}</p>
                            <div class="notification-tags">
                                <span class="tag {{ $notification->data['type'] ?? 'system' }}">
                                    {{ ucfirst(str_replace('_', ' ', $notification->data['type'] ?? 'System')) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No notifications found.</div>
                @endforelse
            </div>

            <div class="pagination">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
