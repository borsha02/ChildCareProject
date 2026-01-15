<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Caregiver Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/messages.css'])
    <style>
        /* Caregiver Branding Overrides */
        .sidebar {
            background: linear-gradient(180deg, #059669 0%, #047857 100%);
        }
        .nav-item.active {
            border-left-color: #fbbf24;
            background: rgba(255, 255, 255, 0.15);
        }
        .nav-item:hover {
            border-left-color: #fbbf24;
        }
        .mobile-toggle {
            background: #059669;
        }
        .icon-btn:hover {
            background: #059669;
        }
    </style>
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
                    <a href="{{ route('caregiver.dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('caregiver.assigned') }}" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Assigned Children</span>
                    </a>
                    <a href="{{ route('caregiver.schedule') }}" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>My Schedule</span>
                    </a>
                    <a href="{{ route('caregiver.attendance') }}" class="nav-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Activities</div>
                    <a href="{{ route('caregiver.daily-reports') }}" class="nav-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                    <a href="{{ route('caregiver.health') }}" class="nav-item">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Records</span>
                    </a>
                    <a href="{{ route('caregiver.events') }}" class="nav-item">
                        <i class="fas fa-calendar-days"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('caregiver.ratings') }}" class="nav-item">
                        <i class="fas fa-star"></i>
                        <span>Ratings</span>
                    </a>
                    <a href="{{ route('caregiver.messages') }}" class="nav-item active">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        @php
                            $unreadMessagesTotal = collect($conversations)->sum('unread_count');
                        @endphp
                        @if($unreadMessagesTotal > 0)
                            <span class="badge">{{ $unreadMessagesTotal }}</span>
                        @endif
                    </a>
                    <a href="{{ route('caregiver.notifications') }}" class="nav-item">
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
                    <a href="{{ route('caregiver.leave') }}" class="nav-item">
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

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Messages</h1>
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
                            </div>
                            <div class="conversations-list">
                                @forelse($conversations as $index => $conversation)
                                    @php
                                        $partner = $conversation['partner'];
                                        $latestMessage = $conversation['latest_message'];
                                        $unreadCount = $conversation['unread_count'];
                                        
                                        // Show "Administrator" for admins instead of their name
                                        $isAdmin = $partner->role === 'admin';
                                        $displayName = $isAdmin ? 'Administrator' : $partner->name;
                                        $displayRole = $isAdmin ? 'Admin' : ucfirst($partner->role);
                                        $avatarName = $isAdmin ? 'Admin' : $partner->name;
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($avatarName) . '&background=059669&color=fff';
                                        $messagePreview = $latestMessage ? Str::limit($latestMessage->message, 40) : 'No messages yet';
                                        $timeAgo = $latestMessage ? $latestMessage->created_at->diffForHumans() : '';
                                    @endphp
                                    <div class="conversation-item {{ $index === 0 ? 'active' : '' }}" 
                                         data-partner-id="{{ $partner->id }}"
                                         data-partner-name="{{ $displayName }}"
                                         data-partner-role="{{ $displayRole }}">
                                        <div class="conversation-avatar">
                                            <img src="{{ $avatarUrl }}" alt="{{ $displayName }}">
                                        </div>
                                        <div class="conversation-info">
                                            <h4>{{ $displayName }}</h4>
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
                                    $isFirstAdmin = $firstPartner->role === 'admin';
                                    $firstDisplayName = $isFirstAdmin ? 'Administrator' : $firstPartner->name;
                                    $firstDisplayRole = $isFirstAdmin ? 'Admin' : ucfirst($firstPartner->role);
                                    $firstAvatarName = $isFirstAdmin ? 'Admin' : $firstPartner->name;
                                    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($firstAvatarName) . '&background=059669&color=fff';
                                @endphp
                                <div class="chat-header">
                                    <div class="chat-user-info">
                                        <div class="chat-avatar">
                                            <img src="{{ $avatarUrl }}" alt="{{ $firstPartner->name }}" id="chatAvatarImg">
                                        </div>
                                        <div class="chat-user-details">
                                            <h3 id="chatUserName">{{ $firstDisplayName }}</h3>
                                            <p id="chatUserRole">{{ $firstDisplayRole }}</p>
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
                                        <p>No messages yet.</p>
                                    </div>
                                @endif
                            </div>

                            @if(count($conversations) > 0)
                                <div class="chat-input-area">
                                    <input type="file" id="fileInput" style="display: none;" accept="image/*,.pdf,.doc,.docx,.txt">
                                    <button class="attach-btn" title="Attach file" id="attachBtn">
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

    <script>
        // Global variables
        let currentPartnerId = null;
        const csrfToken = '{{ csrf_token() }}';

        // Load conversation on page load if there are conversations
        document.addEventListener('DOMContentLoaded', function() {
            const firstConversation = document.querySelector('.conversation-item');
            if (firstConversation) {
                currentPartnerId = firstConversation.dataset.partnerId;
                loadConversation(currentPartnerId);
            }
        });

        // Conversation switching
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');

                // Get partner info
                currentPartnerId = this.dataset.partnerId;
                const partnerName = this.dataset.partnerName;
                const partnerRole = this.dataset.partnerRole;
                const avatarUrl = this.querySelector('.conversation-avatar img').src;

                // Update chat header
                document.getElementById('chatUserName').textContent = partnerName;
                const roleEl = document.getElementById('chatUserRole');
                if (roleEl) roleEl.textContent = partnerRole;
                document.getElementById('chatAvatarImg').src = avatarUrl;

                // Load conversation messages
                loadConversation(currentPartnerId);

                // Remove unread badge
                const unreadBadge = this.querySelector('.unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            });
        });

        // Load conversation messages
        function loadConversation(partnerId) {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '<div class="message-date">Loading messages...</div>';

            fetch(`/caregiver/messages/conversation/${partnerId}`, {
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

        // Display messages in chat area
        function displayMessages(messages) {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';

            if (messages.length === 0) {
                chatMessages.innerHTML = `
                    <div class="empty-chat">
                        <i class="fas fa-comments" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                        <p style="color: #999;">No messages yet. Start the conversation!</p>
                    </div>
                `;
                return;
            }

            let currentDate = null;

            messages.forEach(message => {
                const messageDate = new Date(message.created_at).toLocaleDateString();
                
                // Add date separator if date changed
                if (messageDate !== currentDate) {
                    currentDate = messageDate;
                    const dateDiv = document.createElement('div');
                    dateDiv.className = 'message-date';
                    dateDiv.textContent = formatDate(message.created_at);
                    chatMessages.appendChild(dateDiv);
                }

                // Create message element
                const messageDiv = document.createElement('div');
                const isSent = message.sender_id == {{ auth()->id() }};
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
                                ${formatAttachment(message)}
                            </div>
                            <span class="message-time">${messageTime}</span>
                        </div>
                    `;
                } else {
                    const senderAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender.name)}&background=059669&color=fff`;
                    messageDiv.innerHTML = `
                        <div class="message-avatar">
                            <img src="${senderAvatar}" alt="${escapeHtml(message.sender.name)}">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <p>${escapeHtml(message.message)}</p>
                                ${formatAttachment(message)}
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
            if (!messageInput || (!messageInput.value.trim() && !selectedFile) || !currentPartnerId) {
                return;
            }

            const messageText = messageInput.value.trim();
            const formData = new FormData();
            formData.append('receiver_id', currentPartnerId);
            formData.append('message', messageText);
            if (selectedFile) {
                formData.append('attachment', selectedFile);
            }
            
            // Clear input and attachment immediately for responsive feel
            messageInput.value = '';
            removeFileAttachment();

            fetch(`/caregiver/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to chat
                    appendMessage(data.message, true);
                    
                    // Update conversation preview
                    updateConversationPreview(currentPartnerId, messageText || 'Attachment');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
                messageInput.value = messageText; // Restore message
            });
        }

        // Append message to chat area
        function appendMessage(message, isSent) {
            const chatMessages = document.getElementById('chatMessages');
            
            // Remove empty chat message if exists
            const emptyChat = chatMessages.querySelector('.empty-chat');
            if (emptyChat) {
                emptyChat.remove();
            }

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
                            ${formatAttachment(message)}
                        </div>
                        <span class="message-time">${messageTime}</span>
                    </div>
                `;
            } else {
                const senderAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender.name)}&background=059669&color=fff`;
                messageDiv.innerHTML = `
                    <div class="message-avatar">
                        <img src="${senderAvatar}" alt="${escapeHtml(message.sender.name)}">
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p>${escapeHtml(message.message)}</p>
                            ${formatAttachment(message)}
                        </div>
                        <span class="message-time">${messageTime}</span>
                    </div>
                `;
            }

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Update conversation preview
        function updateConversationPreview(partnerId, messageText) {
            const conversation = document.querySelector(`[data-partner-id="${partnerId}"]`);
            if (conversation) {
                const preview = conversation.querySelector('.conversation-info p');
                if (preview) {
                    preview.textContent = messageText.substring(0, 40) + (messageText.length > 40 ? '...' : '');
                }
                const time = conversation.querySelector('.time');
                if (time) {
                    time.textContent = 'Just now';
                }
                
                // Move to top to indicate recent activity
                const list = conversation.parentElement;
                list.insertBefore(conversation, list.firstChild);
                
                // Ensure active class remains if it was active
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                conversation.classList.add('active');
            }
        }

        // Helper functions
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
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatAttachment(message) {
            if (!message.attachment) return '';
            
            const url = `/storage/${message.attachment}`;
            const isImage = message.attachment_type && message.attachment_type.startsWith('image/');
            
            if (isImage) {
                return `
                    <div class="message-attachment image">
                        <a href="${url}" target="_blank">
                            <img src="${url}" alt="Attachment">
                        </a>
                    </div>
                `;
            } else {
                const icon = getFileIcon(message.attachment_type || '');
                const fileName = message.attachment.split('/').pop().split('_').slice(1).join('_') || 'Attachment';
                
                return `
                    <div class="message-attachment file">
                        <a href="${url}" target="_blank">
                            <i class="${icon}"></i>
                            <span>${escapeHtml(fileName)}</span>
                            <i class="fas fa-download" style="font-size: 12px; margin-left: auto; opacity: 0.6;"></i>
                        </a>
                    </div>
                `;
            }
        }

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
                if (sidebar && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Emoji picker functionality
        const emojiBtn = document.querySelector('.emoji-btn');
        const emojis = ['😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🥰', '😍', '🤩', '😘', '😗', '😚', '😙', '🥲', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '😶‍🌫️', '🥴', '😵', '🤯', '🤠', '🥳', '🥸', '😎', '🤓', '🧐', '😕', '😟', '🙁', '☹️', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '👍', '👎', '👌', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '👇', '☝️', '👋', '🤚', '🖐️', '✋', '🖖', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💅', '🤳', '💪', '🦾', '🦿', '🦵', '🦶', '👂', '🦻', '👃', '🧠', '🫀', '🫁', '🦷', '🦴', '👀', '👁️', '👅', '👄', '💋', '🩸', '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '☮️', '✝️', '☪️', '🕉️', '☸️', '✡️', '🔯', '🕎', '☯️', '☦️', '🛐', '⛎', '♈', '♉', '♊', '♋', '♌', '♍', '♎', '♏', '♐', '♑', '♒', '♓', '🆔', '⚛️', '🉑', '☢️', '☣️', '📴', '📳', '🈶', '🈚', '🈸', '🈺', '🈷️', '✴️', '🆚', '💮', '🉐', '㊙️', '㊗️', '🈴', '🈵', '🈹', '🈲', '🅰️', '🅱️', '🆎', '🆑', '🅾️', '🆘', '❌', '⭕', '🛑', '⛔', '📛', '🚫', '💯', '💢', '♨️', '🚷', '🚯', '🚳', '🚱', '🔞', '📵', '🚭', '❗', '❕', '❓', '❔', '‼️', '⁉️', '🔅', '🔆', '〽️', '⚠️', '🚸', '🔱', '⚜️', '🔰', '♻️', '✅', '🈯', '💹', '❇️', '✳️', '❎', '🌐', '💠', '🔠', '🔡', '🔢', '🔣', '🔤', '🅿️', '🆗', '🆙', '🆒', '🆕', '🆓', '0️⃣', '1️⃣', '2️⃣', '3️⃣', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '🔟', '🔢'];
        
        let emojiPicker = null;

        if (emojiBtn) {
            emojiBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                
                // Remove existing picker if any
                if (emojiPicker) {
                    emojiPicker.remove();
                    emojiPicker = null;
                    return;
                }

                // Create emoji picker
                emojiPicker = document.createElement('div');
                emojiPicker.style.cssText = `
                    position: absolute;
                    bottom: 70px;
                    right: 60px;
                    background: white;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 12px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                    display: grid;
                    grid-template-columns: repeat(8, 1fr);
                    gap: 4px;
                    max-height: 300px;
                    overflow-y: auto;
                    z-index: 1000;
                    width: 320px;
                `;

                emojis.forEach(emoji => {
                    const emojiSpan = document.createElement('span');
                    emojiSpan.textContent = emoji;
                    emojiSpan.style.cssText = `
                        cursor: pointer;
                        font-size: 24px;
                        padding: 8px;
                        border-radius: 6px;
                        text-align: center;
                        transition: background 0.2s;
                    `;
                    emojiSpan.addEventListener('mouseenter', () => {
                        emojiSpan.style.background = '#f3f4f6';
                    });
                    emojiSpan.addEventListener('mouseleave', () => {
                        emojiSpan.style.background = 'transparent';
                    });
                    emojiSpan.addEventListener('click', () => {
                        const input = document.getElementById('messageInput');
                        if (input) {
                            input.value += emoji;
                            input.focus();
                        }
                        emojiPicker.remove();
                        emojiPicker = null;
                    });
                    emojiPicker.appendChild(emojiSpan);
                });

                document.querySelector('.chat-input-area').appendChild(emojiPicker);
            });
        }

        // Close emoji picker when clicking outside
        document.addEventListener('click', (e) => {
            if (emojiPicker && !e.target.closest('.emoji-btn') && !e.target.closest('.chat-input-area')) {
                emojiPicker.remove();
                emojiPicker = null;
            }
        });

        // File attachment functionality
        const attachBtn = document.getElementById('attachBtn');
        const fileInput = document.getElementById('fileInput');
        let selectedFile = null;
        let filePreview = null;

        if (attachBtn && fileInput) {
            attachBtn.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (max 10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be less than 10MB');
                        fileInput.value = '';
                        return;
                    }

                    selectedFile = file;
                    showFilePreview(file);
                }
            });
        }

        function showFilePreview(file) {
            // Remove existing preview if any
            if (filePreview) {
                filePreview.remove();
            }

            // Create preview element
            filePreview = document.createElement('div');
            filePreview.style.cssText = `
                position: absolute;
                bottom: 100%;
                left: 20px;
                right: 20px;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 10px 15px;
                box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 1000;
                margin-bottom: 10px;
            `;

            const fileIcon = getFileIcon(file.type);
            const fileName = file.name.length > 25 ? file.name.substring(0, 25) + '...' : file.name;
            const fileSize = formatFileSize(file.size);

            filePreview.innerHTML = `
                <i class="${fileIcon}" style="font-size: 24px; color: #059669;"></i>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 600; font-size: 14px; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${fileName}</div>
                    <div style="font-size: 12px; color: #666;">${fileSize}</div>
                </div>
                <button onclick="removeFileAttachment()" style="background: none; border: none; cursor: pointer; color: #ef4444; font-size: 18px; padding: 0; width: 24px; height: 24px;">
                    <i class="fas fa-times"></i>
                </button>
            `;

            document.querySelector('.chat-input-area').appendChild(filePreview);
        }

        function removeFileAttachment() {
            if (filePreview) {
                filePreview.remove();
                filePreview = null;
            }
            selectedFile = null;
            fileInput.value = '';
        }

        function getFileIcon(fileType) {
            if (fileType.startsWith('image/')) return 'fas fa-image';
            if (fileType.includes('pdf')) return 'fas fa-file-pdf';
            if (fileType.includes('word') || fileType.includes('document')) return 'fas fa-file-word';
            if (fileType.includes('text')) return 'fas fa-file-alt';
            return 'fas fa-file';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Make removeFileAttachment globally accessible
        window.removeFileAttachment = removeFileAttachment;
    </script>
</body>
</html>
