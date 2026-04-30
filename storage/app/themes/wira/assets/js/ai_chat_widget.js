// Enhanced AI Chat Widget with Clear Error Messages
(function () {
    'use strict';

    // Wait for DOM to be ready before initializing
    document.addEventListener('DOMContentLoaded', function () {
        // Check if DesaAIAssistant class is available
        if (typeof DesaAIAssistant === 'undefined') {
            console.error('DesaAIAssistant class is not loaded. Make sure ai_chat.js is loaded before ai_chat_widget.js');
            return;
        }

        // Initialize AI Assistant
        const aiAssistant = new DesaAIAssistant({
            apiUrl: 'https://ai-assistant.digidesa.id',
            apiKey: '1b7b39be-a750-48ad-9090-629b1c6fd6e9',
            villageUrl: window.location.origin
        });

        // DOM Elements
        const toggleBtn = document.getElementById('toggle-chat');
        const chatWindow = document.getElementById('chat-window');
        const closeBtn = document.getElementById('close-chat');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');
        const messagesContainer = document.getElementById('chat-messages');
        const tooltip = document.getElementById('ai-chat-tooltip');

        // Check if required elements exist
        if (!toggleBtn || !chatWindow || !chatForm || !messagesContainer) {
            console.warn('AI Chat Widget: Required DOM elements not found');
            return;
        }

        // Restore chat history
        if (aiAssistant.conversationHistory.length > 0) {
            aiAssistant.conversationHistory.forEach(msg => {
                const isUser = msg.role === 'user';
                addMessage(msg.content, isUser, false);
            });
            // Scroll to bottom after restoration
            setTimeout(scrollToBottom, 100);
        }

        // Server status
        let serverStatus = 'checking';

        // Add status indicator to header
        // Status indicator is now in the blade template, no need to create it dynamically

        // Update status indicator
        function updateStatus(status, message = null) {
            const dot = document.getElementById('status-dot');
            const text = document.getElementById('status-text');

            if (!dot || !text) return;

            serverStatus = status;

            switch (status) {
                case 'online':
                    dot.className = 'w-2 h-2 rounded-full bg-green-400';
                    text.textContent = message || 'Online • Siap membantu';
                    break;
                case 'offline':
                    dot.className = 'w-2 h-2 rounded-full bg-red-400';
                    text.textContent = message || 'Offline • Server tidak tersedia';
                    break;
                case 'error':
                    dot.className = 'w-2 h-2 rounded-full bg-orange-400';
                    text.textContent = message || 'Error • Terjadi masalah';
                    break;
                case 'checking':
                    dot.className = 'w-2 h-2 rounded-full bg-yellow-400 animate-pulse';
                    text.textContent = message || 'Memeriksa koneksi...';
                    break;
            }
        }

        // Check API availability on load
        async function checkAPIAvailability() {
            // Skip health check - endpoint doesn't exist yet
            // Just assume online and let actual API calls handle errors
            updateStatus('online', 'Siap membantu');
            return true;

            /* Disabled until /health endpoint is available
            updateStatus('checking');

            try {
                const result = await aiAssistant.testConnection();

                if (result.available) {
                    updateStatus('online');
                    removeServerErrorNotice();
                    return true;
                } else {
                    updateStatus('offline', result.message);
                    showServerErrorNotice({
                        title: result.message,
                        message: result.details || 'Server AI tidak dapat dijangkau.',
                        type: 'connection'
                    });
                    return false;
                }
            } catch (error) {
                updateStatus('error');
                showServerErrorNotice({
                    title: '⚠️ Tidak Dapat Memeriksa Koneksi',
                    message: 'Tidak dapat menghubungi server untuk memeriksa status.',
                    type: 'error'
                });
                return false;
            }
            */
        }

        // Show server error notice
        function showServerErrorNotice(errorInfo) {
            // Remove existing notice
            removeServerErrorNotice();

            const notice = document.createElement('div');
            notice.id = 'server-error-notice';
            notice.className = 'bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg mb-3 animate-fade-in';

            let iconColor = 'red';
            if (errorInfo.type === 'connection') iconColor = 'orange';
            if (errorInfo.type === 'cors') iconColor = 'purple';

            notice.innerHTML = `
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-${iconColor}-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-bold text-${iconColor}-800 mb-1">${errorInfo.title}</p>
                    <p class="text-xs text-${iconColor}-700 whitespace-pre-wrap">${errorInfo.message}</p>
                    <div class="mt-3 flex gap-2">
                        <button onclick="location.reload()" 
                            class="text-xs px-3 py-1.5 bg-${iconColor}-500 hover:bg-${iconColor}-600 text-white rounded-lg transition">
                            🔄 Refresh Halaman
                        </button>
                        <button onclick="document.getElementById('server-error-notice').remove()" 
                            class="text-xs px-3 py-1.5 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        `;

            // Insert after welcome message
            const welcomeMsg = messagesContainer.querySelector('.flex.gap-2');
            if (welcomeMsg && welcomeMsg.nextSibling) {
                messagesContainer.insertBefore(notice, welcomeMsg.nextSibling);
            } else {
                messagesContainer.appendChild(notice);
            }

            scrollToBottom();
        }

        // Remove server error notice
        function removeServerErrorNotice() {
            const notice = document.getElementById('server-error-notice');
            if (notice) notice.remove();
        }

        // Show error message in chat
        function showErrorMessage(errorInfo) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'flex gap-2';

            errorDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 bg-red-100 border border-red-200" style="width: 32px; height: 32px;">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="bg-red-50 p-4 rounded-2xl rounded-tl-none shadow-sm border border-red-200 max-w-[85%]">
                <p class="text-sm font-bold text-red-800 mb-2">${errorInfo.title}</p>
                <p class="text-xs text-red-700 whitespace-pre-wrap">${errorInfo.message}</p>
                ${errorInfo.type === 'connection' || errorInfo.type === 'server_error' ? `
                    <button onclick="location.reload()" 
                        class="mt-3 text-xs px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                        🔄 Coba Lagi (Refresh)
                    </button>
                ` : ''}
            </div>
        `;

            messagesContainer.appendChild(errorDiv);
            scrollToBottom();

            // Update status indicator
            updateStatus('error', errorInfo.title);
        }

        // Add message to chat
        function addMessage(content, isUser = false, isStreaming = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-2 ' + (isUser ? 'justify-end' : '');

            if (!isUser) {
                const avatar = document.createElement('div');
                avatar.className = 'w-8 h-8 rounded-full flex items-center justify-center shrink-0 border border-green-200 overflow-hidden';
                avatar.style.cssText = 'width: 32px; height: 32px; min-width: 32px; min-height: 32px;';
                avatar.innerHTML = `<img src="${window.DESA_AI_CONFIG?.iconUrl || '/assets/ai-icon.png'}" class="w-full h-full object-cover">`;
                messageDiv.appendChild(avatar);
            }

            const bubble = document.createElement('div');
            bubble.className = isUser
                ? 'bg-green-600 text-white p-3 rounded-2xl rounded-tr-none max-w-[85%] shadow-sm'
                : 'bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 max-w-[85%]';

            const contentContainer = document.createElement('div');
            contentContainer.className = 'text-sm markdown-content ' + (isUser ? 'text-white' : 'text-gray-700');

            // For user messages, use plain text. For AI messages, render markdown
            if (isUser) {
                contentContainer.textContent = content;
            } else {
                // Initialize marked with options
                if (typeof marked !== 'undefined') {
                    marked.setOptions({
                        breaks: true,
                        gfm: true,
                        headerIds: false,
                        mangle: false
                    });
                    contentContainer.innerHTML = content ? marked.parse(content) : '';
                } else {
                    contentContainer.textContent = content;
                }
            }

            bubble.appendChild(contentContainer);
            messageDiv.appendChild(bubble);

            if (isStreaming) {
                messageDiv.dataset.streaming = 'true';
                const cursor = document.createElement('span');
                cursor.className = 'inline-block w-1 h-4 bg-gray-400 ml-1 animate-pulse';
                contentContainer.appendChild(cursor);
            }

            messagesContainer.appendChild(messageDiv);
            scrollToBottom();

            return { messageDiv, contentContainer };
        }

        // Make chat fullscreen on mobile
        function makeFullscreenOnMobile() {
            // Only apply on mobile devices (screen width < 640px)
            if (window.innerWidth >= 640) return;

            const chatWindow = document.getElementById('chat-window');
            if (!chatWindow) return;

            // Move to body to ensure true fullscreen (not relative to parent)
            document.body.appendChild(chatWindow);

            // Apply fullscreen styles directly to ensure they work
            chatWindow.style.position = 'fixed';
            chatWindow.style.top = '0';
            chatWindow.style.left = '0';
            chatWindow.style.width = '100vw';
            chatWindow.style.height = '100vh';
            chatWindow.style.zIndex = '999999'; // Extremely high z-index
            chatWindow.style.margin = '0';
            chatWindow.style.borderRadius = '0';
            chatWindow.style.maxWidth = '100%';
        }

        // Exit fullscreen on mobile
        function exitFullscreenOnMobile() {
            const chatWindow = document.getElementById('chat-window');
            const widgetContainer = document.getElementById('desa-ai-chat-widget');

            if (!chatWindow || !widgetContainer) return;

            // Move back to widget container
            widgetContainer.insertBefore(chatWindow, widgetContainer.firstChild);

            // Reset styles
            chatWindow.style.position = '';
            chatWindow.style.top = '';
            chatWindow.style.left = '';
            chatWindow.style.width = '';
            chatWindow.style.zIndex = '';
            chatWindow.style.margin = '';
            chatWindow.style.borderRadius = '';
            chatWindow.style.maxWidth = '';

            // Restore original dimensions
            chatWindow.style.height = 'min(80vh, calc(100vh - 120px))';
        }

        // Update streaming message
        function updateStreamingMessage(element, content) {
            const cursor = element.querySelector('.animate-pulse');
            if (cursor) cursor.remove();

            // Render markdown for AI responses
            if (typeof marked !== 'undefined') {
                element.innerHTML = marked.parse(content);
            } else {
                element.textContent = content;
            }

            // Re-add cursor for streaming
            const newCursor = document.createElement('span');
            newCursor.className = 'inline-block w-1 h-4 bg-gray-400 ml-1 animate-pulse';
            element.appendChild(newCursor);

            // Auto-scroll to bottom while streaming
            scrollToBottom();
        }

        // Complete streaming
        function completeStreaming(element) {
            const cursor = element.querySelector('.animate-pulse');
            if (cursor) cursor.remove();

            const messageDiv = element.closest('[data-streaming]');
            if (messageDiv) {
                delete messageDiv.dataset.streaming;
            }
        }

        // Render suggestion chips
        function renderSuggestions(suggestions) {
            if (!suggestions || !suggestions.length) return;

            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.className = 'flex gap-2 overflow-x-auto pb-2 px-1 mb-2 no-scrollbar';

            suggestions.forEach(text => {
                const btn = document.createElement('button');
                btn.className = 'flex-shrink-0 bg-gray-50 hover:bg-white text-gray-600 text-xs px-3 py-1.5 rounded-full border border-gray-200 transition-colors shadow-sm';
                btn.textContent = text;
                btn.onclick = () => {
                    chatInput.value = text;
                    chatInput.style.height = 'auto';
                    chatForm.dispatchEvent(new Event('submit'));
                };
                suggestionsDiv.appendChild(btn);
            });

            messagesContainer.appendChild(suggestionsDiv);
            scrollToBottom();
        }

        // Scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Show loading indicator
        function showLoading() {
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loading-indicator';
            loadingDiv.className = 'flex gap-2';
            loadingDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border border-green-200 overflow-hidden" style="width: 32px; height: 32px;">
                <img src="${window.DESA_AI_CONFIG?.iconUrl || '/assets/ai-icon.png'}" class="w-full h-full object-cover">
            </div>
            <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>
        `;
            messagesContainer.appendChild(loadingDiv);
            scrollToBottom();
        }

        // Remove loading indicator
        function removeLoading() {
            const loading = document.getElementById('loading-indicator');
            if (loading) loading.remove();
        }

        // DOM Elements for send/stop toggle
        const sendIcon = document.getElementById('send-icon');
        const stopIcon = document.getElementById('stop-icon');
        let isCurrentlyStreaming = false;

        // Toggle button to Stop mode
        function showStopButton() {
            if (sendIcon) sendIcon.classList.add('hidden');
            if (stopIcon) stopIcon.classList.remove('hidden');
            sendBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            sendBtn.classList.add('bg-red-500', 'hover:bg-red-600');
            sendBtn.disabled = false;
            sendBtn.type = 'button';
            isCurrentlyStreaming = true;
        }

        // Toggle button back to Send mode
        function showSendButton() {
            if (sendIcon) sendIcon.classList.remove('hidden');
            if (stopIcon) stopIcon.classList.add('hidden');
            sendBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
            sendBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            sendBtn.type = 'submit';
            isCurrentlyStreaming = false;
        }

        // Restore UI after streaming ends
        function restoreAfterStreaming() {
            showSendButton();
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
        }

        // Handle stop button click
        sendBtn.addEventListener('click', function (e) {
            if (isCurrentlyStreaming) {
                e.preventDefault();
                e.stopPropagation();
                aiAssistant.abort();

                // Complete any streaming message
                const streamingMsg = messagesContainer.querySelector('[data-streaming]');
                if (streamingMsg) {
                    const content = streamingMsg.querySelector('.markdown-content');
                    if (content) completeStreaming(content);
                }

                removeLoading();
                restoreAfterStreaming();
            }
        });

        // Handle form submit
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const message = chatInput.value.trim();
            if (!message) return;

            // Intercept "cari peraturan" command
            if (message.toLowerCase().startsWith('cari peraturan')) {
                const keywords = message.slice('cari peraturan'.length).trim();

                // Switch to JDIH mode
                const jdihPanel = document.getElementById('jdih-search-panel');
                if (jdihPanel && jdihPanel.classList.contains('hidden')) {
                    jdihPanel.classList.remove('hidden');
                    jdihPanel.classList.add('flex');
                }

                // Fill and trigger JDIH search
                const jdihKeywordsInput = document.getElementById('jdih-keywords');
                const jdihSearchForm = document.getElementById('jdih-search-form');

                if (jdihKeywordsInput && jdihSearchForm) {
                    jdihKeywordsInput.value = keywords;
                    jdihSearchForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                }

                chatInput.value = '';
                chatInput.style.height = 'auto';
                return;
            }

            // Add user message
            addMessage(message, true);
            chatInput.value = '';
            chatInput.style.height = 'auto';

            // Disable input and show loading
            chatInput.disabled = true;
            sendBtn.disabled = true;

            // Show loading
            showLoading();

            let streamingElement = null;
            let streamingText = '';

            try {
                await aiAssistant.sendMessage(
                    message,
                    // onChunk
                    (chunk) => {
                        if (!streamingElement) {
                            removeLoading();
                            const { contentContainer } = addMessage('', false, true);
                            streamingElement = contentContainer;
                            // Make fullscreen on mobile when first chunk arrives
                            makeFullscreenOnMobile();
                            // Switch to stop button
                            showStopButton();
                        }
                        streamingText += chunk;
                        updateStreamingMessage(streamingElement, streamingText);
                    },
                    // onComplete
                    (fullResponse, cached, suggestions) => {
                        if (streamingElement) {
                            completeStreaming(streamingElement);
                        }

                        // Render suggestions if available
                        if (suggestions && Array.isArray(suggestions) && suggestions.length > 0) {
                            renderSuggestions(suggestions);
                        }

                        // Update status to online on successful response
                        updateStatus('online');
                        restoreAfterStreaming();
                    },
                    // onError
                    (errorInfo) => {
                        removeLoading();
                        showErrorMessage(errorInfo);
                        restoreAfterStreaming();
                    }
                );
            } catch (error) {
                removeLoading();
                showErrorMessage({
                    title: '❌ Kesalahan Tidak Terduga',
                    message: error.message,
                    type: 'unknown'
                });
                restoreAfterStreaming();
            }
        });

        // Auto-resize textarea
        chatInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 96) + 'px';
        });

        // Enter to send (Shift+Enter for new line)
        chatInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        // Toggle chat window
        toggleBtn.addEventListener('click', () => {
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                setTimeout(() => {
                    chatWindow.classList.remove('opacity-0', 'translate-y-4');
                }, 10);
                chatInput.focus();

                // Hide tooltip
                if (tooltip) {
                    tooltip.classList.add('opacity-0', 'translate-y-4');
                    setTimeout(() => tooltip.classList.add('hidden'), 300);
                }
            } else {
                chatWindow.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => {
                    chatWindow.classList.add('hidden');
                }, 300);
            }
        });

        // Close chat
        closeBtn.addEventListener('click', () => {
            exitFullscreenOnMobile();
            chatWindow.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
        });

        // Clear history button
        const clearBtn = document.getElementById('clear-chat-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                if (confirm('Apakah Anda yakin ingin menghapus semua riwayat percakapan?')) {
                    aiAssistant.clearHistory();

                    messagesContainer.innerHTML = '';

                    const welcomeHtml = `
            <div class="flex gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border border-green-200 overflow-hidden"
                    style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; flex-shrink: 0;">
                    <img src="${window.DESA_AI_CONFIG?.iconUrl || '/assets/ai-icon.png'}" class="w-full h-full shadow-sm object-cover"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 max-w-[85%]">
                    <p class="text-sm text-gray-700">Riwayat percakapan telah dihapus. Ada yang bisa saya bantu?</p>
                </div>
            </div>`;

                    messagesContainer.innerHTML = welcomeHtml;
                }
            });
        }

        // JDIH Button - Toggle JDIH search panel
        const jdihBtn = document.getElementById('open-jdih-btn');
        const jdihPanel = document.getElementById('jdih-search-panel');
        const exitJdihBtn = document.getElementById('exit-jdih-mode');

        if (jdihBtn && jdihPanel) {
            jdihBtn.addEventListener('click', () => {
                // Toggle panel visibility
                if (jdihPanel.classList.contains('hidden')) {
                    jdihPanel.classList.remove('hidden');
                    jdihPanel.classList.add('flex');
                } else {
                    jdihPanel.classList.add('hidden');
                    jdihPanel.classList.remove('flex');
                }
            });
        }

        if (exitJdihBtn && jdihPanel) {
            exitJdihBtn.addEventListener('click', () => {
                jdihPanel.classList.add('hidden');
                jdihPanel.classList.remove('flex');
            });
        }

        // Show tooltip after delay
        setTimeout(() => {
            if (tooltip && !localStorage.getItem('ai-tooltip-dismissed')) {
                tooltip.classList.remove('hidden');
                setTimeout(() => {
                    tooltip.classList.remove('opacity-0', 'translate-y-4');
                }, 500);
            }
        }, 2000);

        // Initialize
        checkAPIAvailability();

        // Periodically check connection status
        setInterval(() => {
            checkAPIAvailability();
        }, 60000); // Check every minute

        // Add CSS for animations and markdown styling
        const style = document.createElement('style');
        style.textContent = `
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Markdown Content Styling */
        .markdown-content {
            line-height: 1.6;
        }
        .markdown-content p {
            margin: 0.5em 0;
        }
        .markdown-content p:first-child {
            margin-top: 0;
        }
        .markdown-content p:last-child {
            margin-bottom: 0;
        }
        .markdown-content strong {
            font-weight: 600;
            color: inherit;
        }
        .markdown-content em {
            font-style: italic;
        }
        .markdown-content ul, .markdown-content ol {
            margin: 0.5em 0;
            padding-left: 1.5em;
        }
        .markdown-content li {
            margin: 0.25em 0;
        }
        .markdown-content a {
            color: #059669;
            text-decoration: underline;
            word-break: break-word;
        }
        .markdown-content a:hover {
            color: #047857;
        }
        .markdown-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.5em 0;
        }
        .markdown-content code {
            background-color: #f3f4f6;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875em;
            font-family: monospace;
        }
        .markdown-content pre {
            background-color: #f3f4f6;
            padding: 0.75rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 0.5em 0;
        }
        .markdown-content pre code {
            background-color: transparent;
            padding: 0;
        }
        .markdown-content h1, .markdown-content h2, .markdown-content h3,
        .markdown-content h4, .markdown-content h5, .markdown-content h6 {
            font-weight: 600;
            margin: 0.75em 0 0.5em 0;
            line-height: 1.3;
        }
        .markdown-content h1 { font-size: 1.5em; }
        .markdown-content h2 { font-size: 1.3em; }
        .markdown-content h3 { font-size: 1.1em; }
        .markdown-content blockquote {
            border-left: 3px solid #d1d5db;
            padding-left: 1em;
            margin: 0.5em 0;
            color: #6b7280;
        }
        .markdown-content hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 1em 0;
        }
    `;
        document.head.appendChild(style);

    }); // End DOMContentLoaded
})();