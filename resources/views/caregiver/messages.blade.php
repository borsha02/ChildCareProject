@extends('layouts.caregiver')

@section('title', 'Messages')

@section('styles')
@vite(['resources/css/caregiver/dashboard.css', 'resources/css/caregiver/messages.css'])
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
            <h1>Messages</h1>
        </div>
        <div class="top-bar-actions">
            <div class="search-box">
                <input type="text" placeholder="Search messages...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>

    <div class="content-area p-0">
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
                                $parent = $conversation['parent'];
                                $latestMessage = $conversation['latest_message'];
                                $unreadCount = $conversation['unread_count'];
                                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($parent->name) . '&background=10b981&color=fff';
                                $messagePreview = $latestMessage ? Str::limit($latestMessage->message, 40) : 'No messages yet';
                                $timeAgo = $latestMessage ? $latestMessage->created_at->diffForHumans() : '';
                            @endphp
                            <div class="conversation-item {{ $index === 0 ? 'active' : '' }}" 
                                 data-parent-id="{{ $parent->id }}"
                                 data-parent-name="{{ $parent->name }}">
                                <div class="conversation-avatar">
                                    <img src="{{ $avatarUrl }}" alt="{{ $parent->name }}">
                                </div>
                                <div class="conversation-info">
                                    <h4>{{ $parent->name }}</h4>
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
                                <p>No parents to message yet. Children will be assigned to you by the admin.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="chat-panel" id="chatPanel">
                    @if(count($conversations) > 0)
                        @php
                            $firstParent = $conversations[0]['parent'];
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($firstParent->name) . '&background=10b981&color=fff';
                        @endphp
                        <div class="chat-header">
                            <div class="chat-user-info">
                                <div class="chat-avatar">
                                    <img src="{{ $avatarUrl }}" alt="{{ $firstParent->name }}" id="chatAvatarImg">
                                </div>
                                <div class="chat-user-details">
                                    <h3 id="chatUserName">{{ $firstParent->name }}</h3>
                                    <p id="chatUserRole">Parent</p>
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
                                    <p>Select a parent to start messaging</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="chat-messages" id="chatMessages">
                        @if(count($conversations) > 0)
                            <div class="message-date">Loading messages...</div>
                        @else
                            <div class="empty-chat">
                                <i class="fas fa-comments empty-chat-icon"></i>
                                <p>No conversations yet</p>
                            </div>
                        @endif
                    </div>

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
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentParentId = null;
        const csrfToken = '{{ csrf_token() }}';

        // Load conversation on page load if there are conversations
        document.addEventListener('DOMContentLoaded', function() {
            const firstConversation = document.querySelector('.conversation-item');
            if (firstConversation) {
                currentParentId = firstConversation.dataset.parentId;
                loadConversation(currentParentId);
            }
        });

        // Conversation switching
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');

                // Get parent info
                currentParentId = this.dataset.parentId;
                const parentName = this.dataset.parentName;
                const avatarUrl = this.querySelector('.conversation-avatar img').src;

                // Update chat header
                document.getElementById('chatUserName').textContent = parentName;
                document.getElementById('chatAvatarImg').src = avatarUrl;

                // Load conversation messages
                loadConversation(currentParentId);

                // Remove unread badge
                const unreadBadge = this.querySelector('.unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            });
        });

        // Load conversation messages
        function loadConversation(parentId) {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '<div class="message-date">Loading messages...</div>';

            fetch(`/caregiver/messages/conversation/${parentId}`, {
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
                        <i class="fas fa-comments empty-chat-icon"></i>
                        <p class="empty-chat-text">No messages yet. Start the conversation!</p>
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
                            </div>
                            <span class="message-time">${messageTime}</span>
                        </div>
                    `;
                } else {
                    const senderAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender.name)}&background=10b981&color=fff`;
                    messageDiv.innerHTML = `
                        <div class="message-avatar">
                            <img src="${senderAvatar}" alt="${escapeHtml(message.sender.name)}">
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
            if (!messageInput || !messageInput.value.trim() || !currentParentId) {
                return;
            }

            const messageText = messageInput.value.trim();
            messageInput.value = '';

            fetch('/caregiver/messages/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    receiver_id: currentParentId,
                    message: messageText,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to chat
                    appendMessage(data.message, true);
                    
                    // Update conversation preview
                    updateConversationPreview(currentParentId, messageText);
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
                        </div>
                        <span class="message-time">${messageTime}</span>
                    </div>
                `;
            } else {
                const senderAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender.name)}&background=10b981&color=fff`;
                messageDiv.innerHTML = `
                    <div class="message-avatar">
                        <img src="${senderAvatar}" alt="${escapeHtml(message.sender.name)}">
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
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Update conversation preview
        function updateConversationPreview(parentId, messageText) {
            const conversation = document.querySelector(`[data-parent-id="${parentId}"]`);
            if (conversation) {
                const preview = conversation.querySelector('.conversation-info p');
                if (preview) {
                    preview.textContent = messageText.substring(0, 40) + (messageText.length > 40 ? '...' : '');
                }
                const time = conversation.querySelector('.time');
                if (time) {
                    time.textContent = 'Just now';
                }
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
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
@endsection
