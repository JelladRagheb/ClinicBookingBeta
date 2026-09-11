<button @click="toggleDarkMode()"
    class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700"
    aria-label="Toggle dark mode">
    {{-- Moon Icon (Light Mode UI) --}}
    <svg id="moon-icon" class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
        <path d="M17.292 8.605a5.5 5.5 0 0 0-7.23-7.23 5.5 5.5 0 0 0-4.004 9.07 9 9 0 1 0 11.234-11.234Z"></path>
    </svg>
    {{-- Sun Icon (Dark Mode UI) --}}
    <svg id="sun-icon" class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
        <path
            d="M10 2a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1zm4 4a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1zm-4 8a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zm4 4a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zM4 4a1 1 0 0 1 1 1h1a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1V5zm12 0a1 1 0 0 1 1 1v1a1 1 0 1 1 0 2h-1a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm-4 12a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zM5 12a1 1 0 0 1 1 1h1a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1v-1zm12-1a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zM6 10a4 4 0 1 1 8 0 4 4 0 0 1-8 0z">
        </path>
    </svg>
</button>
