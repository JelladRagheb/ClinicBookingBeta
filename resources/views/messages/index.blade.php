<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-6 h-[calc(100vh-160px)]" x-data="{ showNewMessageModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl h-full flex border border-slate-200 dark:border-slate-700">

                <!-- Inbox Sidebar -->
                <div
                    class="w-full sm:w-1/3 flex flex-col border-r border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/10">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Chats</h3>
                        <button @click="showNewMessageModal = true"
                            class="p-2 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full hover:bg-primary-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <button id="request-mic">Allow Microphone</button>
                    <!-- Thread List -->
                    <div id="threads-container" class="flex-1 overflow-y-auto">
                        @if ($threads->count() > 0)
                            @foreach ($threads as $t)
                                <a href="{{ route('messages.show', $t->id) }}" id="thread-link-{{ $t->id }}"
                                    class="flex items-center gap-3 p-4 hover:bg-white dark:hover:bg-slate-700/50 transition-all border-b border-slate-100 dark:border-slate-700/50 {{ isset($thread) && $thread->id == $t->id ? 'bg-white dark:bg-slate-700/50 ring-1 ring-inset ring-primary-500/20' : '' }}">
                                    <div class="relative">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($t->participantsString(Auth::id())) }}&background=random"
                                            class="w-12 h-12 rounded-full border-2 border-white dark:border-slate-800 shadow-sm">
                                        <div id="unread-dot-{{ $t->id }}"
                                            class="{{ $t->isUnread(Auth::id()) ? '' : 'hidden' }}">
                                            <span
                                                class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-primary-500 ring-2 ring-white dark:ring-slate-800 animate-pulse"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-baseline">
                                            <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                                {{ $t->participantsString(Auth::id()) }}
                                            </h4>
                                            <span
                                                class="text-[10px] text-slate-500 uppercase font-semibold whitespace-nowrap ml-2">
                                                {{ $t->updated_at->shortRelativeDiffForHumans() }}
                                            </span>
                                        </div>
                                        <p id="latest-message-{{ $t->id }}"
                                            class="text-xs truncate mt-0.5 {{ $t->isUnread(Auth::id()) ? 'font-black text-slate-950 dark:text-white' : 'text-slate-500' }}">
                                            @if ($t->latest_message)
                                                {{ $t->latest_message->user_id == Auth::id() ? 'You: ' : '' }}{{ $t->latest_message->body }}
                                            @else
                                                No messages yet
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="p-8 text-center text-slate-500">
                                <p class="text-sm">No messages yet.</p>
                                <button @click="showNewMessageModal = true"
                                    class="mt-4 text-primary-600 font-medium hover:underline text-sm">Start a
                                    conversation</button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="hidden sm:flex flex-1 flex-col bg-white dark:bg-slate-800">
                    @if (isset($thread))
                        <!-- Chat Header -->
                        <div
                            class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($thread->participantsString(Auth::id())) }}&background=random"
                                    class="w-10 h-10 rounded-full">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white">
                                        {{ $thread->participantsString(Auth::id()) }}</h3>
                                    {{-- <p class="text-[10px] text-green-500 font-bold uppercase tracking-wider">Active Now
                                    </p> --}}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-2 text-slate-400 hover:text-primary-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-primary-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Messages Feed -->
                        <div id="messages-feed"
                            class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-slate-900/50">
                            @foreach ($messages as $message)
                                <div
                                    class="flex {{ $message->user_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div
                                        class="flex gap-2 max-w-[80%] {{ $message->user_id == Auth::id() ? 'flex-row-reverse' : 'flex-row' }}">
                                        @if ($message->user_id != Auth::id())
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->user->full_name) }}&background=random"
                                                class="w-8 h-8 rounded-full self-end mb-1">
                                        @endif
                                        <div>
                                            <div
                                                class="p-3 rounded-2xl text-sm shadow-sm
                                                {{ $message->user_id == Auth::id()
                                                    ? 'bg-primary-600 text-white rounded-br-none'
                                                    : 'bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-bl-none border border-slate-100 dark:border-slate-600' }}">
                                                {{ $message->body }}
                                            </div>
                                            <p
                                                class="text-[10px] text-slate-400 mt-1 {{ $message->user_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                                {{ $message->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
                            <form id="chat-form" action="{{ route('messages.update', $thread->id) }}" method="post"
                                class="flex items-center gap-3">
                                {{ method_field('put') }}
                                {{ csrf_field() }}
                                <button type="button" class="text-slate-400 hover:text-primary-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                                <div class="flex-1 relative">
                                    <textarea name="message" id="message-input" rows="1" placeholder="Type a message..."
                                        class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all py-2 px-4 resize-none"></textarea>
                                </div>
                                <button type="submit"
                                    class="p-2 bg-primary-600 text-white rounded-full hover:bg-primary-700 shadow-md transition-all">
                                    <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- No thread selected -->
                        <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                            <div
                                class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Your Messages</h3>
                            <p class="text-slate-500 mt-2 max-w-xs">Select a conversation or start a new one to begin
                                chatting.</p>
                            <button @click="showNewMessageModal = true"
                                class="mt-6 px-6 py-2 bg-primary-600 text-white rounded-full font-bold hover:bg-primary-700 transition-all shadow-lg">
                                Send Message
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- New Message Modal -->
        <div x-show="showNewMessageModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div x-show="showNewMessageModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showNewMessageModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Content -->
                <div x-show="showNewMessageModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[70]">
                    <form action="{{ route('messages.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">New Message</h3>

                            <!-- User Selection -->
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Recipient</label>
                                    <select name="recipient_id"
                                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }}
                                                ({{ $user->roles->first()?->name ?? 'User' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Message</label>
                                    <textarea name="message" rows="4"
                                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Type your first message..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 flex justify-end gap-3">
                            <button type="button" @click="showNewMessageModal = false"
                                class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900">Cancel</button>
                            <button type="submit"
                                class="px-6 py-2 bg-primary-600 text-white rounded-lg font-bold hover:bg-primary-700 transition-all shadow-md">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-scroll to bottom of messages
            const feed = document.getElementById('messages-feed');

            function scrollToBottom() {
                if (feed) {
                    feed.scrollTop = feed.scrollHeight;
                }
            }
            scrollToBottom();

            // AJAX Messaging Logic
            const chatForm = document.getElementById('chat-form');
            const textarea = document.getElementById('message-input');

            async function sendMessage() {
                const body = textarea.value.trim();
                if (body === '') return;

                const url = chatForm.action;
                const token = chatForm.querySelector('input[name="_token"]').value;
                const method = chatForm.querySelector('input[name="_method"]')?.value || 'POST';

                // 1. Optimistic UI: Append locally immediately
                const myName = "{{ auth()->user()->first_name }}";
                const myId = {{ auth()->id() }};

                const messageHtml = `
                <div class="flex justify-end">
                    <div class="flex gap-2 max-w-[80%] flex-row-reverse">
                        <div>
                            <div class="p-3 rounded-2xl text-sm shadow-sm bg-primary-600 text-white rounded-br-none">
                                ${body}
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 text-right">sending...</p>
                        </div>
                    </div>
                </div>
            `;
                feed.insertAdjacentHTML('beforeend', messageHtml);
                scrollToBottom();

                // Move this thread to the top of the sidebar immediately
                if (typeof updateThreadSidebar === 'function') {
                    updateThreadSidebar({
                        threadId: {{ isset($thread) ? $thread->id : 'null' }},
                        message: body,
                        senderId: myId
                    });
                }

                // 2. Clear input
                textarea.value = '';
                textarea.style.height = 'auto';

                // 3. Send to server
                try {
                    const response = await fetch(url, {
                        method: 'POST', // Always POST for Laravel with _method field
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: body,
                            _method: method,
                            _token: token
                        })
                    });

                    if (!response.ok) throw new Error('Send failed');

                    // Update "sending..." text to "Just now" or similar
                    const lastMsgStatus = feed.lastElementChild.querySelector('p');
                    if (lastMsgStatus) lastMsgStatus.innerText = 'Sent';

                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                }
            }

            if (chatForm) {
                chatForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    sendMessage();
                });
            }

            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });

                textarea.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });
            }

            const processedMessageIds = new Set();
            // Function to append message to feed
            function appendMessage(e) {
                // Deduplication check
                if (e.messageId && processedMessageIds.has(e.messageId)) {
                    console.log('Skipping duplicate message:', e.messageId);
                    return;
                }
                if (e.messageId) processedMessageIds.add(e.messageId);

                const messageHtml = `
                <div class="flex justify-start">
                    <div class="flex gap-2 max-w-[80%] flex-row">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(e.senderName)}&background=random" 
                                class="w-8 h-8 rounded-full self-end mb-1">
                        <div>
                            <div class="p-3 rounded-2xl text-sm shadow-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-bl-none border border-slate-100 dark:border-slate-600">
                                ${e.message}
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 text-left">
                                Just now
                            </p>
                        </div>
                    </div>
                </div>
            `;

                if (feed) {
                    feed.insertAdjacentHTML('beforeend', messageHtml);
                    scrollToBottom();
                }
            }

            // Function to update thread in sidebar
            function updateThreadSidebar(e) {
                const threadLink = document.getElementById(`thread-link-${e.threadId}`);
                const threadsContainer = document.getElementById('threads-container');
                const currentThreadId = @json(isset($thread) ? $thread->id : null);

                if (threadLink && threadsContainer) {
                    // 1. Move to top
                    threadsContainer.prepend(threadLink);

                    // 2. Update snippet text
                    const snippet = document.getElementById(`latest-message-${e.threadId}`);
                    if (snippet) {
                        const senderPrefix = e.senderId == {{ auth()->id() }} ? 'You: ' : '';
                        snippet.innerText = senderPrefix + e.message;

                        // 3. If it's not the currently open thread, make it BOLD and add DOT
                        if (e.threadId != currentThreadId) {
                            snippet.classList.remove('text-slate-500');
                            snippet.classList.add('font-black', 'text-slate-950', 'dark:text-white');

                            const dot = document.getElementById(`unread-dot-${e.threadId}`);
                            if (dot) dot.classList.remove('hidden');
                        }
                    }
                } else {
                    // If thread doesn't exist (e.g. very first message), reload to show it
                    window.location.reload();
                }
            }

            // Real-time listener
            window.addEventListener('load', () => {
                if (window.Echo) {
                    const userId = {{ auth()->id() }};
                    console.log('Echo connected, setting up listeners for User:', userId);

                    // 1. Thread-specific Channel
                    @if (isset($thread))
                        console.log('Listening for specific thread: {{ $thread->id }}');
                        window.Echo.private('messenger.thread.{{ $thread->id }}')
                            .listen('.MessageSent', (e) => {
                                console.log('Incoming thread message:', e);
                                if (e.senderId != userId) appendMessage(e);
                                updateThreadSidebar(e);
                            });
                    @endif

                    // 2. Global User-specific Channel
                    window.Echo.private('App.Models.User.' + userId)
                        .listen('.MessageSent', (e) => {
                            console.log('Incoming global message:', e);
                            @if (isset($thread))
                                if (e.threadId == {{ $thread->id }}) {
                                    if (e.senderId != userId) appendMessage(e);
                                }
                            @endif
                            updateThreadSidebar(e);
                        });

                } else {
                    console.warn('Laravel Echo is not initialized after page load.');
                }
            });

            document.getElementById('request-mic').addEventListener('click', async () => {
                try {
                    navigator.permissions.query({
                        name: 'microphone'
                    }).then(result => {
                        console.log(result.state); // "granted", "prompt", or "denied"
                    });
                    // This triggers the browser prompt
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                } catch (err) {
                    console.error('Permission denied or error:', err);
                    alert('Microphone access is needed for this feature.');
                }
            });

            // window.Echo.private(`voice-call.user.${userId}`)
            //     .listen('.SignalingEvent', (e) => {
            //         console.log('Incoming signaling data:', e);
            //     });
        </script>
    @endpush
</x-app-layout>
