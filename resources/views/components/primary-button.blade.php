@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-primary-500 hover:to-primary-400 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150 shadow-lg shadow-primary-500/30']) }}>
    {{ $slot }}
</button>
