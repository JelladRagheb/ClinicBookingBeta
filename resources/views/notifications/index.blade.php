<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('All Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Notifications</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">View your latest alerts and appointment
                        updates.</p>
                </div>
                <form action="{{ route('notifications.read') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Mark All as Read
                    </button>
                </form>
            </div>

            <div
                class="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-md border border-slate-200 dark:border-slate-700">
                <ul role="list" class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($notifications as $notification)
                        @php
                            $actionUrl = $notification->action_url ?? '#';
                            // @dd($actionUrl);
                        @endphp
                        <li x-data="{
                            markAndRedirect(id, url) {
                                if (!url || url === '#') return;
                        
                                fetch('{{ route('notifications.read') }}/' + id, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).finally(() => {
                                    window.location.href = url;
                                });
                            }
                        }"
                            @click="markAndRedirect('{{ $notification->id }}', '{{ $actionUrl }}')"
                            class="{{ is_null($notification->read_at) ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }} transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <span
                                                class="inline-flex items-center justify-center h-10 w-10 rounded-full {{ is_null($notification->read_at) ? 'bg-primary-100 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                                                @if ($notification->type === 'App\Notifications\DoctorScheduleNotification')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                                {{ $notification->title[app()->getLocale()] ?? ($notification->title['en'] ?? 'Notification') }}
                                                @if (is_null($notification->read_at))
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                        New
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                {{ $notification->message[app()->getLocale()] ?? ($notification->message['en'] ?? 'You have a new alert.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex flex-col items-end">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                        @if (is_null($notification->read_at))
                                            <button @click.stop="markAndRedirect('{{ $notification->id }}', '#')"
                                                class="mt-2 text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">
                                                Mark as read
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li>
                            <div class="px-4 py-8 sm:px-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No notifications
                                </h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">You're all caught up! There
                                    are no alerts to display right now.</p>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
