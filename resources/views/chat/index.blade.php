@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden" style="height: calc(100vh - 200px);">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <div>
                    <h1 class="text-xl font-bold">AI Assistant</h1>
                    <p class="text-sm opacity-90">Powered by Ollama</p>
                </div>
            </div>
            
            <!-- Model Selector -->
            <div class="flex items-center space-x-2">
                <label for="modelSelect" class="text-sm">Model:</label>
                <select id="modelSelect" class="bg-white bg-opacity-20 text-white rounded px-3 py-1 text-sm border border-white border-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    <option value="llama3.2:1b">llama3.2:1b</option>
                </select>
                <button id="refreshModels" class="p-2 hover:bg-white hover:bg-opacity-20 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900" style="height: calc(100% - 180px);">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                        AI
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-3 shadow">
                        <p class="text-gray-800 dark:text-gray-200">
                            Hello! I'm your AI assistant. How can I help you today?
                        </p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">Just now</span>
                </div>
            </div>
        </div>

        <!-- Chat Input Area -->
        <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
            <form id="chatForm" class="flex items-end space-x-2">
                <div class="flex-1">
                    <textarea 
                        id="messageInput" 
                        rows="2" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Type your message here..."
                        required
                    ></textarea>
                </div>
                <button 
                    type="submit" 
                    id="sendButton"
                    class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-200 font-medium flex items-center space-x-2 h-[72px]"
                >
                    <span>Send</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            
            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden mt-2 flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm">AI is thinking...</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Chat functionality
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const sendButton = document.getElementById('sendButton');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const modelSelect = document.getElementById('modelSelect');
    const refreshModels = document.getElementById('refreshModels');

    // Load available models on page load
    loadModels();

    // Handle form submission
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        
        // Clear input
        messageInput.value = '';
        
        // Disable send button and show loading
        sendButton.disabled = true;
        loadingIndicator.classList.remove('hidden');

        try {
            // Send message to server
            const response = await fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: message,
                    model: modelSelect.value
                })
            });

            const data = await response.json();

            if (data.success) {
                // Add AI response to chat
                addMessage(data.response, 'ai');
            } else {
                // Show error message
                addMessage('Sorry, I encountered an error: ' + (data.error || 'Unknown error'), 'error');
            }
        } catch (error) {
            console.error('Chat error:', error);
            addMessage('Sorry, I could not connect to the AI assistant. Please make sure Ollama is running.', 'error');
        } finally {
            // Re-enable send button and hide loading
            sendButton.disabled = false;
            loadingIndicator.classList.add('hidden');
            messageInput.focus();
        }
    });

    // Add message to chat
    function addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-3';
        
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        if (type === 'user') {
            messageDiv.innerHTML = `
                <div class="flex-1"></div>
                <div class="flex-shrink-0 order-2">
                    <div class="w-10 h-10 rounded-full bg-gray-600 dark:bg-gray-400 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1 order-1">
                    <div class="bg-blue-600 text-white rounded-lg px-4 py-3 shadow ml-auto max-w-md">
                        <p class="whitespace-pre-wrap break-words">${escapeHtml(text)}</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block text-right">${timeString}</span>
                </div>
            `;
        } else if (type === 'ai') {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                        AI
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-3 shadow">
                        <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words">${escapeHtml(text)}</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">${timeString}</span>
                </div>
            `;
        } else if (type === 'error') {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border border-red-200 dark:border-red-800 rounded-lg px-4 py-3">
                        <p class="text-red-800 dark:text-red-300 whitespace-pre-wrap break-words">${escapeHtml(text)}</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">${timeString}</span>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Load available models
    async function loadModels() {
        try {
            const response = await fetch('{{ route('chat.models') }}');
            const data = await response.json();

            if (data.success && data.models.length > 0) {
                modelSelect.innerHTML = '';
                data.models.forEach(model => {
                    const option = document.createElement('option');
                    option.value = model.name;
                    option.textContent = model.name;
                    modelSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading models:', error);
        }
    }

    // Refresh models button
    refreshModels.addEventListener('click', () => {
        loadModels();
    });

    // Allow Enter to send, Shift+Enter for new line
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endsection
