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
        <h1>Notifications</h1>
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
                    <span class="badge">5 New</span>
                </div>
                <div class="header-actions">
                    <button class="mark-read-btn">Mark all as read</button>
                    <select class="filter-select">
                        <option value="all">All</option>
                        <option value="unread">Unread</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div class="notification-list">
                <!-- Unread & High Priority -->
                <div class="notification-item unread high-priority">
                    <div class="notification-icon warning">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-top">
                            <h4>Emergency Drill Tomorrow</h4>
                            <span class="time">10 mins ago</span>
                        </div>
                        <p>There will be a mandatory fire drill tomorrow at 10:00 AM. Please ensure all children are
                            prepared.</p>
                        <div class="notification-tags">
                            <span class="tag urgent">Urgent</span>
                            <span class="tag admin">Admin</span>
                        </div>
                    </div>
                    <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>

                <!-- Unread -->
                <div class="notification-item unread">
                    <div class="notification-icon message">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-top">
                            <h4>New Message from Mrs. Johnson</h4>
                            <span class="time">1 hour ago</span>
                        </div>
                        <p>Regarding Lucas's medication schedule for next week. Please review the updated health record.</p>
                        <div class="notification-tags">
                            <span class="tag message">Message</span>
                        </div>
                    </div>
                    <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>

                <!-- Read -->
                <div class="notification-item">
                    <div class="notification-icon event">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-top">
                            <h4>Event Reminder: Christmas Party</h4>
                            <span class="time">3 hours ago</span>
                        </div>
                        <p>The Christmas Party is scheduled for tomorrow. Setup begins at 8:30 AM in the Main Hall.</p>
                        <div class="notification-tags">
                            <span class="tag event">Event</span>
                        </div>
                    </div>
                    <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>

                <div class="notification-item">
                    <div class="notification-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-top">
                            <h4>Daily Report Submitted</h4>
                            <span class="time">Yesterday</span>
                        </div>
                        <p>Your daily report for Class A has been successfully submitted and approved by the supervisor.</p>
                        <div class="notification-tags">
                            <span class="tag system">System</span>
                        </div>
                    </div>
                    <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>

                <div class="notification-item">
                    <div class="notification-icon info">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-top">
                            <h4>Policy Update: Pick-up Procedures</h4>
                            <span class="time">2 days ago</span>
                        </div>
                        <p>New safety protocols for child pick-up will be effective starting next Monday. Please review the
                            handbook.</p>
                        <div class="notification-tags">
                            <span class="tag admin">Policy</span>
                        </div>
                    </div>
                    <button class="action-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>

            <div class="pagination">
                <button class="page-btn disabled"><i class="fas fa-chevron-left"></i></button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
@endsection
