@extends('layouts.caregiver')

@section('title', 'Assigned Children')

@section('styles')
    @vite(['resources/css/caregiver/assigned-children.css'])
@endsection

@section('styles')
    @vite(['resources/css/caregiver/assigned-children.css'])
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
            <h1>Assigned Children</h1>
        </div>
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
                    <h3>{{ $children->count() }}</h3>
                    <p>Total Assigned</p>
                </div>
            </div>
            <!-- Dynamic stats for attendance to be implemented with Attendance feature -->
             <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $presentCount }}</h3>
                    <p>Present Today</p>
                </div>
            </div>
        </div>

        <!-- Children List -->
        <div class="card">
            <div class="card-header">
                <h3>All Assigned Children</h3>
                <div class="filter-container">
                    <select id="classFilter" class="class-filter-select">
                        <option value="">All Classes</option>
                        @foreach($children->pluck('class')->unique() as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="children-list" id="childrenList">
                @forelse($children as $child)
                <div class="child-item" data-class="{{ $child->class }}">
                    <div class="child-avatar" style="background: {{ '#' . substr(md5($child->first_name . $child->last_name), 0, 6) }};">
                        {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                    </div>
                    <div class="child-info">
                        <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                        <p>
                            Age: {{ \Carbon\Carbon::parse($child->dob)->age }} years • 
                            Class: {{ $child->class }} • 
                            Parent: {{ $child->parent->name ?? 'N/A' }} 
                            @if($child->parent && $child->parent->phone)
                                • <a href="tel:{{ $child->parent->phone }}"><i class="fas fa-phone"></i> {{ $child->parent->phone }}</a>
                            @endif
                        </p>
                    </div>
                    <div class="child-actions">
                        <span class="status-badge {{ $child->status }}">{{ ucfirst($child->status) }}</span>
                        <button class="action-btn view" onclick="viewChild({{ json_encode($child) }})" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <p>No children assigned yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- View Child Details Modal -->
    <div class="modal-wrap modal-overlay modal-hidden" id="viewChildModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Child Details</h2>
                <button class="close-modal-btn" onclick="closeViewModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Details populated via JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('classFilter').addEventListener('change', function(e) {
            const classVal = e.target.value;
            const items = document.querySelectorAll('.child-item');
            
            items.forEach(item => {
                if (!classVal || item.dataset.class === classVal) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Search functionality if you have a search input
        const searchInput = document.querySelector('.search-box input');
        if(searchInput){
            searchInput.addEventListener('input', function(e){
                const val = e.target.value.toLowerCase();
                 const items = document.querySelectorAll('.child-item');
                 items.forEach(item => {
                     const text = item.textContent.toLowerCase();
                     if(text.includes(val)){
                         item.style.display = 'flex';
                     } else {
                         item.style.display = 'none';
                     }
                 });
            });
        }

        // View Modal Functions
        function viewChild(child) {
            const modal = document.getElementById('viewChildModal');
            const body = document.getElementById('viewModalBody');
            
            // Calculate age
            const dob = new Date(child.dob);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            body.innerHTML = `
                <div class="view-details-grid">
                    <div class="detail-group">
                        <label>Full Name</label>
                        <p>${child.first_name} ${child.last_name}</p>
                    </div>
                     <div class="detail-group">
                        <label>Status</label>
                        <span class="status-badge ${child.status}">${child.status.charAt(0).toUpperCase() + child.status.slice(1)}</span>
                    </div>
                    <div class="detail-group">
                        <label>Age</label>
                        <p>${age} years</p>
                    </div>
                    <div class="detail-group">
                        <label>Gender</label>
                        <p>${child.gender.charAt(0).toUpperCase() + child.gender.slice(1)}</p>
                    </div>
                    <div class="detail-group">
                        <label>Class</label>
                        <p>${child.class}</p>
                    </div>
                    <div class="detail-group">
                        <label>Package</label>
                        <p>${child.package ? child.package.charAt(0).toUpperCase() + child.package.slice(1) : 'Monthly'}</p>
                    </div>
                    <div class="detail-group">
                        <label>Blood Group</label>
                        <p>${child.blood_group || 'N/A'}</p>
                    </div>
                    <div class="detail-group">
                        <label>Parent/Guardian</label>
                        <p>${child.parent ? child.parent.name : 'N/A'}</p>
                    </div>
                    <div class="detail-group">
                        <label>Contact</label>
                        <p>${child.parent ? '<a href="tel:' + child.parent.phone + '">' + child.parent.phone + '</a>' : 'N/A'}</p>
                    </div>
                    <div class="detail-group full-width">
                        <label>Medical Notes</label>
                        <p class="note-box">${child.medical_notes || 'No medical notes available.'}</p>
                    </div>
                     <div class="detail-group full-width">
                        <label>Allergies</label>
                        <p class="allergy-box">${child.allergies || 'No allergies listed.'}</p>
                    </div>
                </div>
            `;

            modal.classList.remove('modal-hidden');
            modal.classList.add('modal-visible');
        }

        function closeViewModal() {
            const modal = document.getElementById('viewChildModal');
            modal.classList.remove('modal-visible');
            modal.classList.add('modal-hidden');
        }

        // Close view modal on outside click
        document.getElementById('viewChildModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });
    </script>
@endsection
