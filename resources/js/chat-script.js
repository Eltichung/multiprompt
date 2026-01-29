// Chat Interface JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;

    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    body.appendChild(overlay);

    // Toggle sidebar on mobile
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            body.classList.toggle('sidebar-open');
        });
    }

    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        body.classList.remove('sidebar-open');
    });

    // Menu toggle in sidebar
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }

    // Send message functionality
    const sendBtn = document.querySelector('.send-btn');
    const messageInput = document.querySelector('.message-input');
    const messagesArea = document.querySelector('.messages-area');

    function sendMessage() {
        const messageText = messageInput.value.trim();
        if (messageText === '') return;

        // Create user message
        const userMessage = createMessageElement('user', messageText);
        messagesArea.appendChild(userMessage);

        // Clear input
        messageInput.value = '';

        // Scroll to bottom
        messagesArea.scrollTop = messagesArea.scrollHeight;

        // Simulate assistant response (in real app, this would be an API call)
        setTimeout(() => {
            const assistantMessage = createMessageElement('assistant', 'Xin chào! Tôi đã nhận được tin nhắn của bạn. Đây là một ví dụ demo.');
            messagesArea.appendChild(assistantMessage);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }, 1000);
    }

    // Send on button click
    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }

    // Send on Enter key
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // Create message element
    function createMessageElement(type, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;

        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        avatar.innerHTML = type === 'user'
            ? '<i class="fas fa-user"></i>'
            : '<i class="fas fa-robot"></i>';

        const content = document.createElement('div');
        content.className = 'message-content';

        const paragraph = document.createElement('p');
        paragraph.textContent = text;
        content.appendChild(paragraph);

        if (type === 'assistant') {
            const actions = document.createElement('div');
            actions.className = 'message-actions';
            actions.innerHTML = `
                <button class="msg-action-btn" title="Copy"><i class="far fa-copy"></i></button>
                <button class="msg-action-btn" title="Like"><i class="far fa-thumbs-up"></i></button>
                <button class="msg-action-btn" title="Dislike"><i class="far fa-thumbs-down"></i></button>
                <button class="msg-action-btn" title="Regenerate"><i class="fas fa-rotate"></i></button>
                <button class="msg-action-btn" title="More"><i class="fas fa-ellipsis"></i></button>
            `;
            content.appendChild(actions);
        }

        messageDiv.appendChild(avatar);
        messageDiv.appendChild(content);

        return messageDiv;
    }

    // Option buttons click handler
    const optionButtons = document.querySelectorAll('.option-btn');
    optionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            messageInput.value = this.textContent;
            messageInput.focus();
        });
    });

    // Message action buttons
    document.addEventListener('click', function(e) {
        // Copy message
        if (e.target.closest('.msg-action-btn[title="Copy"]')) {
            const messageContent = e.target.closest('.message-content').querySelector('p').textContent;
            navigator.clipboard.writeText(messageContent).then(() => {
                showNotification('Đã sao chép!');
            });
        }

        // Like/Dislike handlers
        if (e.target.closest('.msg-action-btn[title="Like"]') ||
            e.target.closest('.msg-action-btn[title="Dislike"]')) {
            e.target.closest('.msg-action-btn').classList.toggle('active');
        }

        // Regenerate message
        if (e.target.closest('.msg-action-btn[title="Regenerate"]')) {
            showNotification('Đang tạo lại câu trả lời...');
        }
    });

    // Close personality feedback
    const feedbackClose = document.querySelector('.feedback-close');
    if (feedbackClose) {
        feedbackClose.addEventListener('click', function() {
            this.closest('.personality-feedback').style.display = 'none';
        });
    }

    // New chat button
    const newChatBtn = document.querySelector('.new-chat-btn');
    if (newChatBtn) {
        newChatBtn.addEventListener('click', function() {
            if (confirm('Bạn có muốn bắt đầu cuộc trò chuyện mới?')) {
                // In real app, this would clear messages and start fresh
                messagesArea.innerHTML = '';
                showNotification('Đã tạo cuộc trò chuyện mới!');
            }
        });
    }

    // Chat item click handler
    const chatItems = document.querySelectorAll('.chat-item');
    chatItems.forEach(item => {
        item.addEventListener('click', function() {
            chatItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Update chat title
            const chatTitle = document.querySelector('.chat-title');
            if (chatTitle) {
                chatTitle.textContent = this.textContent;
            }

            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('open');
                body.classList.remove('sidebar-open');
            }
        });
    });

    // Voice input button
    const voiceBtn = document.querySelector('.voice-btn');
    if (voiceBtn) {
        voiceBtn.addEventListener('click', function() {
            showNotification('Chức năng nhập giọng nói đang được phát triển');
        });
    }

    // Attach button
    const attachBtn = document.querySelector('.attach-btn');
    if (attachBtn) {
        attachBtn.addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*,.pdf,.doc,.docx';
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    showNotification(`Đã chọn file: ${file.name}`);
                }
            };
            input.click();
        });
    }

    // Share button
    const shareBtn = document.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: 'ChatGPT Conversation',
                    text: 'Check out this conversation',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                showNotification('Chức năng chia sẻ không được hỗ trợ trên trình duyệt này');
            }
        });
    }

    // Notification function
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .msg-action-btn.active {
            background-color: var(--accent-color);
            color: white;
        }
    `;
    document.head.appendChild(style);

    // Auto-resize textarea (if you want to make input expandable)
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            messageInput.focus();
        }

        // Escape to close sidebar on mobile
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            body.classList.remove('sidebar-open');
        }
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768 && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                body.classList.remove('sidebar-open');
            }
        }, 250);
    });

    // Smooth scroll for messages
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                messagesArea.scrollTo({
                    top: messagesArea.scrollHeight,
                    behavior: 'smooth'
                });
            }
        });
    });

    if (messagesArea) {
        observer.observe(messagesArea, {
            childList: true
        });
    }

    console.log('Chat interface loaded successfully!');
    const checkAll = document.getElementById('check-all');
    const items = document.querySelectorAll('.check-item');
    items.forEach(item => {
        item.checked = checkAll.checked;
    });

    checkAll.addEventListener('change', () => {
        items.forEach(item => {
            item.checked = checkAll.checked;
        });
    });

    items.forEach(item => {
        item.addEventListener('change', () => {
            const allChecked = [...items].every(i => i.checked);
            checkAll.checked = allChecked;
        });
    });
});
