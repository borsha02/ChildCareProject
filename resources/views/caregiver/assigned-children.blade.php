@extends('layouts.caregiver')

@section('title', 'Assigned Children')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Assigned Children</h1>
        <div class="top-bar-actions">
            <div class="search-box">
                <input type="text" placeholder="Search children...">
                <i class="fas fa-search"></i>
            </div>
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
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
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3>12</h3>
                    <p>Total Assigned</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-details">
                    <h3>10</h3>
                    <p>Present Today</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-details">
                    <h3>2</h3>
                    <p>Absent Today</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-birthday-cake"></i>
                </div>
                <div class="stat-details">
                    <h3>1</h3>
                    <p>Birthday This Week</p>
                </div>
            </div>
        </div>

        <!-- Children List -->
        <div class="card">
            <div class="card-header">
                <h3>All Assigned Children</h3>
                <div style="display: flex; gap: 10px;">
                    <select style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                        <option>All Classes</option>
                        <option>Preschool A</option>
                        <option>Toddler A</option>
                        <option>Toddler B</option>
                    </select>
                    <select style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                        <option>All Status</option>
                        <option>Present</option>
                        <option>Absent</option>
                    </select>
                </div>
            </div>
            <div class="children-list">
                <div class="child-item">
                    <div class="child-avatar">EM</div>
                    <div class="child-info">
                        <h4>Emma Martinez</h4>
                        <p>Age: 4 years • Class: Preschool A • Parent: Maria Martinez</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">LJ</div>
                    <div class="child-info">
                        <h4>Lucas Johnson</h4>
                        <p>Age: 3 years • Class: Toddler B • Parent: Sarah Johnson</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);">OW</div>
                    <div class="child-info">
                        <h4>Olivia Williams</h4>
                        <p>Age: 5 years • Class: Preschool A • Parent: James Williams</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">NB</div>
                    <div class="child-info">
                        <h4>Noah Brown</h4>
                        <p>Age: 2 years • Class: Toddler A • Parent: Emily Brown</p>
                    </div>
                    <span class="status-badge absent">Absent</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #10b981, #059669);">AD</div>
                    <div class="child-info">
                        <h4>Ava Davis</h4>
                        <p>Age: 4 years • Class: Preschool A • Parent: Michael Davis</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #ef4444, #dc2626);">LM</div>
                    <div class="child-info">
                        <h4>Liam Miller</h4>
                        <p>Age: 3 years • Class: Toddler B • Parent: Jennifer Miller</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">SW</div>
                    <div class="child-info">
                        <h4>Sophia Wilson</h4>
                        <p>Age: 5 years • Class: Preschool A • Parent: Robert Wilson</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #f97316, #ea580c);">MM</div>
                    <div class="child-info">
                        <h4>Mason Moore</h4>
                        <p>Age: 2 years • Class: Toddler A • Parent: Lisa Moore</p>
                    </div>
                    <span class="status-badge absent">Absent</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #ec4899, #db2777);">IT</div>
                    <div class="child-info">
                        <h4>Isabella Taylor</h4>
                        <p>Age: 4 years • Class: Preschool A • Parent: David Taylor</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">EA</div>
                    <div class="child-info">
                        <h4>Ethan Anderson</h4>
                        <p>Age: 3 years • Class: Toddler B • Parent: Amanda Anderson</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #a855f7, #9333ea);">MT</div>
                    <div class="child-info">
                        <h4>Mia Thomas</h4>
                        <p>Age: 5 years • Class: Preschool A • Parent: Christopher Thomas</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
                <div class="child-item">
                    <div class="child-avatar" style="background: linear-gradient(135deg, #eab308, #ca8a04);">JJ</div>
                    <div class="child-info">
                        <h4>Jackson Jackson</h4>
                        <p>Age: 2 years • Class: Toddler A • Parent: Jessica Jackson</p>
                    </div>
                    <span class="status-badge present">Present</span>
                </div>
            </div>
        </div>
    </div>
@endsection
