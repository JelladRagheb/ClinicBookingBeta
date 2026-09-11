 @extends('layouts.app')
 <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" con tent="{{ csrf_token() }}">

     @section('title')
         <title>{{ config('app.name', 'ClinicBooking') }}</title>
     @endsection

     <!-- Fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap"
         rel="stylesheet">

     <!-- Scripts -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 </head>
 @section('content')

     <body class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
         <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
             <div
                 class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-slate-800 shadow-xl overflow-hidden sm:rounded-2xl border border-slate-100 dark:border-slate-700 relative">
                 <!-- Decorative Elements -->
                 <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-400 to-primary-600"></div>

                 <div class="flex justify-center mb-8">
                     <a href="/">
                         <x-clinic-logo class="w-20 h-20 fill-current text-primary-600" />
                     </a>
                 </div>

                 {{ $slot }}
             </div>

             <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                 &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
             </div>
         </div>
     </body>
 @endsection

 </html>
