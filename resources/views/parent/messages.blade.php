<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/messages.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-baby"></i>
                    <h2>Childcare</h2>
                </div>
                <div class="user-info">
                    <div class="user-avatar">JD</div>
                    <div class="user-details">
                        <h4>John Doe</h4>
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
                    <a href="{{ route('parent.messages') }}" class="nav-item active">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <span class="badge">3</span>
                    </a>
                    <a href="{{ route('parent.notifications') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">5</span>
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
                    <a href="{{ route('parent.caregivers') }}" class="nav-item">
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
                    <h1>Messages</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search messages...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <button class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            <div class="content-area">
                <div class="messages-container">
                    <div class="messages-layout">
                        <!-- Conversations List -->
                        <div class="conversations-panel">
                            <div class="panel-header">
                                <h2>Conversations</h2>
                                <button class="new-message-btn">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="conversations-list">
                                <div class="conversation-item active">
                                    <div class="conversation-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=4f46e5&color=fff" alt="Sarah Johnson">
                                        <span class="online-indicator"></span>
                                    </div>
                                    <div class="conversation-info">
                                        <h4>Ms. Sarah Johnson</h4>
                                        <p>Great! I'll make sure Emma brings...</p>
                                    </div>
                                    <div class="conversation-meta">
                                        <span class="time">2m</span>
                                        <span class="unread-badge">2</span>
                                    </div>
                                </div>

                                <div class="conversation-item">
                                    <div class="conversation-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=10b981&color=fff" alt="Michael Chen">
                                    </div>
                                    <div class="conversation-info">
                                        <h4>Mr. Michael Chen</h4>
                                        <p>Lucas did great in today's activity!</p>
                                    </div>
                                    <div class="conversation-meta">
                                        <span class="time">1h</span>
                                        <span class="unread-badge">1</span>
                                    </div>
                                </div>

                                <div class="conversation-item">
                                    <div class="conversation-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Admin+Office&background=f59e0b&color=fff" alt="Admin Office">
                                    </div>
                                    <div class="conversation-info">
                                        <h4>Admin Office</h4>
                                        <p>Reminder: Payment due on Dec 31</p>
                                    </div>
                                    <div class="conversation-meta">
                                        <span class="time">3h</span>
                                    </div>
                                </div>

                                <div class="conversation-item">
                                    <div class="conversation-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Emily+Rodriguez&background=ec4899&color=fff" alt="Emily Rodriguez">
                                    </div>
                                    <div class="conversation-info">
                                        <h4>Ms. Emily Rodriguez</h4>
                                        <p>Thank you for the update!</p>
                                    </div>
                                    <div class="conversation-meta">
                                        <span class="time">1d</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Area -->
                        <div class="chat-panel">
                            <div class="chat-header">
                                <div class="chat-user-info">
                                    <div class="chat-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=4f46e5&color=fff" alt="Sarah Johnson">
                                        <span class="online-indicator"></span>
                                    </div>
                                    <div class="chat-user-details">
                                        <h3>Ms. Sarah Johnson</h3>
                                        <p>Emma's Teacher • Online</p>
                                    </div>
                                </div>
                                <div class="chat-actions">
                                    <button class="chat-action-btn" title="More">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="chat-messages">
                                <div class="message-date">Today</div>

                                <div class="message received">
                                    <div class="message-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=4f46e5&color=fff" alt="Sarah Johnson">
                                    </div>
                                    <div class="message-content">
                                        <div class="message-bubble">
                                            <p>Good morning! Just wanted to let you know that Emma did wonderfully in today's art class. She created a beautiful painting!</p>
                                        </div>
                                        <span class="message-time">9:30 AM</span>
                                    </div>
                                </div>

                                <div class="message sent">
                                    <div class="message-content">
                                        <div class="message-bubble">
                                            <p>That's wonderful to hear! She's been talking about art class all week. Thank you for the update!</p>
                                        </div>
                                        <span class="message-time">9:35 AM</span>
                                    </div>
                                </div>

                                <div class="message received">
                                    <div class="message-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=4f46e5&color=fff" alt="Sarah Johnson">
                                    </div>
                                    <div class="message-content">
                                        <div class="message-bubble">
                                            <p>She's very talented! Also, just a reminder that we have the parent-teacher conference scheduled for next week. Please bring Emma's art supplies for the upcoming project.</p>
                                        </div>
                                        <span class="message-time">9:40 AM</span>
                                    </div>
                                </div>

                                <div class="message sent">
                                    <div class="message-content">
                                        <div class="message-bubble">
                                            <p>Great! I'll make sure Emma brings her supplies. Looking forward to the conference!</p>
                                        </div>
                                        <span class="message-time">Just now</span>
                                    </div>
                                </div>

                                <div class="typing-indicator">
                                    <div class="typing-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=4f46e5&color=fff" alt="Sarah Johnson">
                                    </div>
                                    <div class="typing-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="chat-input-area">
                                <button class="attach-btn" title="Attach file">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <input type="text" class="chat-input" placeholder="Type a message...">
                                <button class="emoji-btn" title="Emoji">
                                    <i class="fas fa-smile"></i>
                                </button>
                                <button class="send-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Conversation data
        const conversations = {
            'sarah': {
                name: 'Ms. Sarah Johnson',
                role: "Emma's Teacher",
                status: 'Online',
                avatar: 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=4f46e5&color=fff',
                online: true
            },
            'michael': {
                name: 'Mr. Michael Chen',
                role: "Lucas's Teacher",
                status: 'Online',
                avatar: 'https://ui-avatars.com/api/?name=Michael+Chen&background=10b981&color=fff',
                online: true
            },
            'admin': {
                name: 'Admin Office',
                role: 'Administration',
                status: 'Offline',
                avatar: 'https://ui-avatars.com/api/?name=Admin+Office&background=f59e0b&color=fff',
                online: false
            },
            'emily': {
                name: 'Ms. Emily Rodriguez',
                role: 'Assistant Teacher',
                status: 'Offline',
                avatar: 'https://ui-avatars.com/api/?name=Emily+Rodriguez&background=ec4899&color=fff',
                online: false
            }
        };

        // Conversation switching
        document.querySelectorAll('.conversation-item').forEach((item, index) => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');

                // Get conversation key based on index
                const conversationKeys = ['sarah', 'michael', 'admin', 'emily'];
                const conversationKey = conversationKeys[index];
                const conversation = conversations[conversationKey];

                // Update chat header
                const chatAvatar = document.querySelector('.chat-avatar img');
                const chatName = document.querySelector('.chat-user-details h3');
                const chatStatus = document.querySelector('.chat-user-details p');
                const onlineIndicator = document.querySelector('.chat-avatar .online-indicator');

                chatAvatar.src = conversation.avatar;
                chatName.textContent = conversation.name;
                chatStatus.textContent = conversation.role + ' • ' + conversation.status;

                // Show/hide online indicator
                if (conversation.online) {
                    if (!onlineIndicator) {
                        const indicator = document.createElement('span');
                        indicator.className = 'online-indicator';
                        document.querySelector('.chat-avatar').appendChild(indicator);
                    }
                } else {
                    if (onlineIndicator) {
                        onlineIndicator.remove();
                    }
                }

                // Remove unread badge from clicked conversation
                const unreadBadge = this.querySelector('.unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            });
        });

        // Send message
        const chatInput = document.querySelector('.chat-input');
        const sendBtn = document.querySelector('.send-btn');

        sendBtn.addEventListener('click', () => {
            if (chatInput.value.trim()) {
                // Send message logic
                chatInput.value = '';
            }
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && chatInput.value.trim()) {
                // Send message logic
                chatInput.value = '';
            }
        });

        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
