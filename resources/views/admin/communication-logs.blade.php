<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Logs - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/messages.css'])
    <style>
        /* Override or add admin-specific styles if needed */
        .sidebar {
            background: #1a1c23; /* Admin sidebar color */
        }
        .nav-item.active {
            border-left-color: #3b82f6; /* Admin active color */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Admin Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <h2>Admin Panel</h2>
                </div>
                <div class="user-info">
                    <div class="user-avatar" style="background: #3b82f6;">AD</div>
                    <div class="user-details">
                        <h4>Administrator</h4>
                        <p>System Admin</p>
                    </div>
                </div>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="{{ route('admin.users') }}" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="{{ route('admin.children') }}" class="nav-item">
                        <i class="fas fa-child"></i>
                        <span>Child Records</span>
                    </a>
                    <a href="{{ route('admin.staff') }}" class="nav-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Staff Management</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Operations</div>
                    <a href="{{ route('admin.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('admin.invoices') }}" class="nav-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Invoices</span>
                    </a>
                    <a href="{{ route('admin.payments.pending') }}" class="nav-item">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Approvals</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>

                    <a href="{{ route('admin.communication') }}" class="nav-item active">
                        <i class="fas fa-comments"></i>
                        <span>Communication Logs</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.settings') }}" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('admin.backup') }}" class="nav-item">
                        <i class="fas fa-database"></i>
                        <span>Backup & Restore</span>
                    </a>
                    <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
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
                    <a href="{{ route('admin.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Communication Logs</h1>
                </div>
                <div class="top-bar-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search messages...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <div class="messages-container">
                    <div class="messages-layout">
                        <!-- Conversations List -->
                        <div class="conversations-panel">
                            <div class="panel-header">
                                <h2>Conversations</h2>
                                <button class="new-message-btn" onclick="openMessageModal()" title="New Message / Broadcast">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="conversations-list">
                                @forelse($conversations as $index => $conversation)
                                    @php
                                        $partner = $conversation['partner'];
                                        $latestMessage = $conversation['latest_message'];
                                        $unreadCount = $conversation['unread_count'];
                                        
                                        $avatarName = $partner->name;
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($avatarName) . '&background=3b82f6&color=fff';
                                        $messagePreview = $latestMessage ? Str::limit($latestMessage->message, 40) : 'No messages yet';
                                        $timeAgo = $latestMessage ? $latestMessage->created_at->diffForHumans() : '';
                                    @endphp
                                    <div class="conversation-item {{ $index === 0 ? 'active' : '' }}" 
                                         data-user-id="{{ $partner->id }}"
                                         data-user-name="{{ $partner->name }}"
                                         data-user-role="{{ ucfirst($partner->role) }}">
                                        <div class="conversation-avatar">
                                            <img src="{{ $avatarUrl }}" alt="{{ $partner->name }}">
                                        </div>
                                        <div class="conversation-info">
                                            <h4>{{ $partner->name }}</h4>
                                            <p>{{ $messagePreview }}</p>
                                        </div>
                                        <div class="conversation-meta">
                                            <span class="time">{{ $timeAgo }}</span>
                                            @if($unreadCount > 0)
                                                <span class="unread-badge">{{ $unreadCount }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="no-conversations">
                                        <p>No conversations yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Chat Area -->
                        <div class="chat-panel" id="chatPanel">
                            @if(count($conversations) > 0)
                                @php
                                    $firstPartner = $conversations[0]['partner'];
                                    $avatarName = $firstPartner->name;
                                    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($avatarName) . '&background=3b82f6&color=fff';
                                @endphp
                                <div class="chat-header">
                                    <div class="chat-user-info">
                                        <div class="chat-avatar">
                                            <img src="{{ $avatarUrl }}" alt="{{ $firstPartner->name }}" id="chatAvatarImg">
                                        </div>
                                        <div class="chat-user-details">
                                            <h3 id="chatUserName">{{ $firstPartner->name }}</h3>
                                            <p id="chatUserRole">{{ ucfirst($firstPartner->role) }}</p>
                                        </div>
                                    </div>
                                    <div class="chat-actions">
                                        <button class="chat-action-btn" title="More">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="chat-header">
                                    <div class="chat-user-info">
                                        <div class="chat-user-details">
                                            <h3>No Conversations</h3>
                                            <p>Select a user to start messaging</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="chat-messages" id="chatMessages">
                                @if(count($conversations) > 0)
                                    <div class="message-date">Loading messages...</div>
                                @else
                                    <div class="empty-chat">
                                        <i class="fas fa-comments" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                                        <p>No conversations yet</p>
                                    </div>
                                @endif
                            </div>

                            @if(count($conversations) > 0)
                                <div class="chat-input-area">
                                    <button class="attach-btn" title="Attach file">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <input type="text" class="chat-input" id="messageInput" placeholder="Type a message...">
                                    <button class="emoji-btn" title="Emoji">
                                        <i class="fas fa-smile"></i>
                                    </button>
                                    <button class="send-btn" id="sendMessageBtn">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- New Message / Broadcast Modal -->
    <div id="messageModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div class="modal-content" style="background: white; width: 500px; margin: 100px auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0; color: #333;">New Message</h2>
                <span class="close" onclick="closeMessageModal()" style="cursor: pointer; font-size: 24px; color: #666;">&times;</span>
            </div>
            <form action="{{ route('admin.communication.send') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="recipient_id" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Recipient</label>
                    <select name="recipient_id" id="recipient_id" class="filter-select" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" required>
                        <option value="">Select Recipient...</option>
                        <optgroup label="Broadcast">
                            <option value="all_parents">All Parents</option>
                            <option value="all_staff">All Staff</option>
                        </optgroup>
                        <optgroup label="Parents">
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Staff">
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="message" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Message</label>
                    <textarea name="message" id="modalMessage" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" required placeholder="Type your message here..."></textarea>
                </div>
                <div class="form-actions" style="text-align: right;">
                    <button type="button" onclick="closeMessageModal()" style="padding: 10px 20px; background: #e5e7eb; border: none; border-radius: 6px; cursor: pointer; margin-right: 10px; font-weight: 600;">Cancel</button>
                    <button type="submit" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global variables
        let currentUserId = null; // Renamed from caregiverId for admin context
        const csrfToken = '{{ csrf_token() }}';

        // Modal Functions
        function openMessageModal() {
            document.getElementById('messageModal').style.display = 'block';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Close on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target == modal) {
                closeMessageModal();
            }
        }

        // Load conversation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstConversation = document.querySelector('.conversation-item');
            if (firstConversation) {
                firstConversation.click();
            }
        });

        // Conversation switching
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');

                // Get user info
                currentUserId = this.dataset.userId;
                const userName = this.dataset.userName;
                const userRole = this.dataset.userRole;
                const avatarUrl = this.querySelector('.conversation-avatar img').src;

                // Update chat header
                document.getElementById('chatUserName').textContent = userName;
                document.getElementById('chatUserRole').textContent = userRole;
                document.getElementById('chatAvatarImg').src = avatarUrl;

                // Load conversation messages
                loadConversation(currentUserId);

                // Remove unread badge
                const unreadBadge = this.querySelector('.unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            });
        });

        // Load conversation messages
        function loadConversation(userId) {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '<div class="message-date">Loading messages...</div>';

            fetch(`/admin/communication/conversation/${userId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessages(data.messages);
                }
            })
            .catch(error => {
                console.error('Error loading conversation:', error);
                chatMessages.innerHTML = '<div class="message-date">Error loading messages</div>';
            });
        }

        // Display messages
        function displayMessages(messages) {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';

            if (messages.length === 0) {
                chatMessages.innerHTML = `
                    <div class="empty-chat" style="text-align: center; padding: 40px;">
                        <i class="fas fa-comments" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                        <p style="color: #999;">No messages yet.</p>
                    </div>
                `;
                return;
            }

            let currentDate = null;
            const currentAdminId = {{ auth()->id() }};

            messages.forEach(message => {
                const messageDate = new Date(message.created_at).toLocaleDateString();
                
                // Add date separator
                if (messageDate !== currentDate) {
                    currentDate = messageDate;
                    const dateDiv = document.createElement('div');
                    dateDiv.className = 'message-date';
                    dateDiv.textContent = formatDate(message.created_at);
                    chatMessages.appendChild(dateDiv);
                }

                // Create message element
                const messageDiv = document.createElement('div');
                const isSent = message.sender_id == currentAdminId;
                messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;

                const messageTime = new Date(message.created_at).toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit' 
                });

                if (isSent) {
                    messageDiv.innerHTML = `
                        <div class="message-content">
                            <div class="message-bubble">
                                <p>${escapeHtml(message.message)}</p>
                            </div>
                            <span class="message-time">${messageTime}</span>
                        </div>
                    `;
                } else {
                    const senderName = message.sender.name;
                    const senderAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=3b82f6&color=fff`;
                    messageDiv.innerHTML = `
                        <div class="message-avatar">
                            <img src="${senderAvatar}" alt="${escapeHtml(senderName)}">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <p>${escapeHtml(message.message)}</p>
                            </div>
                            <span class="message-time">${messageTime}</span>
                        </div>
                    `;
                }

                chatMessages.appendChild(messageDiv);
            });

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Send message
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendMessageBtn');

        if (sendBtn) {
            sendBtn.addEventListener('click', sendMessage);
        }

        if (messageInput) {
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }

        function sendMessage() {
            if (!messageInput || !messageInput.value.trim() || !currentUserId) {
                return;
            }

            const messageText = messageInput.value.trim();
            messageInput.value = '';

            fetch('/admin/communication/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    recipient_id: currentUserId,
                    message: messageText,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    appendMessage(data.message, true);
                    updateConversationPreview(currentUserId, messageText);
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
                messageInput.value = messageText;
            });
        }

        function appendMessage(message, isSent) {
            const chatMessages = document.getElementById('chatMessages');
            const emptyChat = chatMessages.querySelector('.empty-chat');
            if (emptyChat) emptyChat.remove();

            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;

            const messageTime = new Date(message.created_at).toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit' 
            });

            if (isSent) {
                messageDiv.innerHTML = `
                    <div class="message-content">
                        <div class="message-bubble">
                            <p>${escapeHtml(message.message)}</p>
                        </div>
                        <span class="message-time">${messageTime}</span>
                    </div>
                `;
            } else {
                // In admin view, we might not have immediate access to receiver name here if we rely on data.message only
                // But for sent messages, it's fine. For received messages (via pusher? no pusher here), we'd reload.
                // This function is mostly called after Sending, so 'isSent' is true.
                // If we implemented real-time, we'd need more data.
                // For now, only 'sent' logic is critical here.
            }

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function updateConversationPreview(userId, messageText) {
            const conversation = document.querySelector(`[data-user-id="${userId}"]`);
            if (conversation) {
                const preview = conversation.querySelector('.conversation-info p');
                if (preview) {
                    preview.textContent = messageText.substring(0, 40) + (messageText.length > 40 ? '...' : '');
                }
                const time = conversation.querySelector('.time');
                if (time) {
                    time.textContent = 'Just now';
                }
                // Update active class just in case
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                conversation.classList.add('active');
                
                // Move to top
                const list = conversation.parentElement;
                list.insertBefore(conversation, list.firstChild);
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            if (date.toDateString() === today.toDateString()) {
                return 'Today';
            } else if (date.toDateString() === yesterday.toDateString()) {
                return 'Yesterday';
            } else {
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Sidebar toggle for mobile
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
        }
    </script>
</body>
</html>
