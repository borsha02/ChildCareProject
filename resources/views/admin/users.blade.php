<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin/users.css'])
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
                    <h1>User Management</h1>
                </div>
                <div class="top-bar-actions">
                    <button class="add-btn" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Add New User
                    </button>
                </div>
            </div>

            <div class="content-area">
                <!-- Users Table -->
                <div class="users-card">
                    <div class="card-header">
                        <h3 id="pageTitle">All Users</h3>
                        <div class="filter-tabs">
                            <button class="tab-btn active" onclick="switchView('users', 'all')">All</button>
                            <button class="tab-btn" onclick="switchView('users', 'admin')">Admins</button>
                            <button class="tab-btn" onclick="switchView('users', 'parent')">Parents</button>
                            <button class="tab-btn" onclick="switchView('users', 'caregiver')">Staff</button>
                            <button class="tab-btn" onclick="switchView('children', 'all')">Children</button>
                            
                            <!-- Hidden Package Filters (Only for Children View) -->
                            <span id="packageFilters" style="display: none; border-left: 1px solid #ddd; padding-left: 10px; margin-left: 5px;">
                                <button class="tab-btn" onclick="filterChildren('monthly')">Monthly Package</button>
                                <button class="tab-btn" onclick="filterChildren('weekly')">Weekly Package</button>
                            </span>
                        </div>
                    </div>

                    <div class="search-box">
                        <input type="text" placeholder="Search by name, email, or phone..." id="searchInput">
                        <i class="fas fa-search"></i>
                    </div>

                    <div id="usersView">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Children</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                @forelse($users as $user)
                                <tr data-role="{{ $user->role }}" data-status="{{ $user->status }}">
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar-small" style="background: {{ '#' . substr(md5($user->name), 0, 6) }};">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div class="user-details-small">
                                                <h4>{{ $user->name }}</h4>
                                                <p>ID: #{{ $user->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td><span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                                    <td>
                                        @if($user->role === 'parent' && $user->children->count() > 0)
                                            <div style="font-size: 0.85em; line-height: 1.4;">
                                                @foreach($user->children as $child)
                                                    <div>
                                                        <strong>{{ $child->first_name }}</strong>
                                                        <span style="color: #6b7280; font-size: 0.9em;">({{ ucfirst($child->package) }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span style="color: #9ca3af;">--</span>
                                        @endif
                                    </td>
                                    <td><span class="status-badge {{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-icon edit" title="Edit" onclick="editUser({{ json_encode($user) }}, '{{ route('admin.users.update', $user->id) }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to {{ $user->status === 'active' ? 'deactivate' : 'activate' }} this user?');">
                                                @csrf
                                                <button type="submit" class="action-icon {{ $user->status === 'active' ? 'delete' : 'approve' }}" title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 20px;">No users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="pagination">
                            {{ $users->links() }}
                        </div>
                    </div>

                    <!-- Children View (Hidden by default) -->
                    <div id="childrenView" style="display: none;">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>Child Name</th>
                                    <th>Age</th>
                                    <th>Class</th>
                                    <th>Parent</th>
                                    <th>Package</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="childrenTableBody">
                                @forelse($enrolledChildren as $child)
                                <tr data-package="{{ strtolower($child->package) }}" data-class="{{ $child->class }}">
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar-small" style="background: {{ '#' . substr(md5($child->first_name . $child->last_name), 0, 6) }};">
                                                {{ strtoupper(substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1)) }}
                                            </div>
                                            <div class="user-details-small">
                                                <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
                                                <p>ID: CH{{ str_pad($child->id, 3, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($child->dob)->age }} yrs</td>
                                    <td>{{ $child->class }}</td>
                                    <td>
                                        @if($child->parent)
                                            {{ $child->parent->name }}
                                            <div style="font-size: 0.8em; color: #666;">{{ $child->parent->phone }}</div>
                                        @else
                                            <span style="color: #999;">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-weight: 500;">{{ ucfirst($child->package) }}</span>
                                        @if($child->package == 'weekly' && $child->duration)
                                            <span style="font-size: 0.8em; color: #666;">({{ $child->duration }} wks)</span>
                                        @endif
                                    </td>
                                    <td><span class="status-badge {{ $child->status }}">{{ ucfirst($child->status) }}</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-icon view" title="View Details" onclick="viewChildDetails({{ json_encode($child) }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.children') }}" class="action-icon edit" title="Go to Child Records" style="text-decoration: none;">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 20px;">No children found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal-overlay" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New User</h2>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Enter full name">
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Enter email address">
                </div>
                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Enter phone number">
                </div>
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="parent">Parent</option>
                        <option value="caregiver">Caregiver</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm password">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Child Details Modal -->
    <div class="modal-overlay" id="childDetailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Child Details</h2>
                <button class="close-modal" onclick="closeChildModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="childDetailsBody">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('userModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Add New User';
            document.querySelector('#userModal form').action = "{{ route('admin.users.create') }}";
            document.querySelector('#userModal form').reset();
            
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        }

        function closeModal() {
            document.getElementById('userModal').classList.remove('active');
        }

        function editUser(user, updateUrl) {
            document.getElementById('userModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Edit User';
            
            const form = document.querySelector('#userModal form');
            form.action = updateUrl;

            let methodInput = document.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }

            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone').value = user.phone;
            document.getElementById('role').value = user.role;
            
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
        }

        function switchView(viewName, filter) {
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => tab.classList.remove('active'));
            if (event.target.tagName === 'BUTTON') {
                event.target.classList.add('active');
            }

            const usersView = document.getElementById('usersView');
            const childrenView = document.getElementById('childrenView');
            const packageFilters = document.getElementById('packageFilters');
            const pageTitle = document.getElementById('pageTitle');
            const searchInput = document.getElementById('searchInput');

            searchInput.value = '';

            if (viewName === 'children') {
                usersView.style.display = 'none';
                childrenView.style.display = 'block';
                packageFilters.style.display = 'inline-block';
                pageTitle.textContent = 'All Children';
                filterChildren('all');
            } else {
                usersView.style.display = 'block';
                childrenView.style.display = 'none';
                packageFilters.style.display = 'none';
                pageTitle.textContent = 'All Users';
                filterUsers(filter);
            }
        }

        function filterUsers(role) {
            const rows = document.querySelectorAll('#usersTableBody tr');
            rows.forEach(row => {
                const show = role === 'all' || row.dataset.role === role;
                row.style.display = show ? '' : 'none';
            });
        }

        function filterChildren(packageType) {
            if (event.target.tagName === 'BUTTON') {
                const packageButtons = document.querySelectorAll('#packageFilters .tab-btn');
                packageButtons.forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
            }

            const rows = document.querySelectorAll('#childrenTableBody tr');
            rows.forEach(row => {
                if (packageType === 'all') {
                    row.style.display = '';
                } else {
                    const rowPackage = row.dataset.package;
                    row.style.display = rowPackage === packageType ? '' : 'none';
                }
            });
        }

        // Child Details Modal
        function viewChildDetails(child) {
            const modal = document.getElementById('childDetailsModal');
            const body = document.getElementById('childDetailsBody');
            
             body.innerHTML = `
                <div class="view-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <strong>Name:</strong> <p>${child.first_name} ${child.last_name}</p>
                    </div>
                    <div>
                         <strong>DOB:</strong> <p>${child.dob.split('T')[0]}</p>
                    </div>
                    <div>
                        <strong>Class:</strong> <p>${child.class}</p>
                    </div>
                     <div>
                        <strong>Package:</strong> <p>${ucFirst(child.package)} ${child.package==='weekly' && child.duration ? '('+child.duration+' wks)' : ''}</p>
                    </div>
                    <div>
                        <strong>Parent Name:</strong> <p>${child.parent ? child.parent.name : 'N/A'}</p>
                    </div>
                     <div>
                        <strong>Parent Phone:</strong> <p>${child.parent ? child.parent.phone : 'N/A'}</p>
                    </div>
                     <div style="grid-column: 1 / -1;">
                        <strong>Medical Notes:</strong> 
                        <p style="background: #f9f9f9; padding: 10px; border-radius: 4px;">${child.medical_notes || 'None'}</p>
                    </div>
                     <div style="grid-column: 1 / -1;">
                        <strong>Allergies:</strong> 
                        <p style="background: #f9f9f9; padding: 10px; border-radius: 4px;">${child.allergies || 'None'}</p>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
        }
        
        function closeChildModal() {
            document.getElementById('childDetailsModal').classList.remove('active');
        }
        
        function ucFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const activeView = document.getElementById('childrenView').style.display === 'block' ? 'children' : 'users';
            
            const tbodyId = activeView === 'children' ? 'childrenTableBody' : 'usersTableBody';
            const rows = document.querySelectorAll(`#${tbodyId} tr`);
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        window.onclick = function(event) {
            const userModal = document.getElementById('userModal');
            const childModal = document.getElementById('childDetailsModal');
            if (event.target == userModal) {
                closeModal();
            }
            if (event.target == childModal) {
                closeChildModal();
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
