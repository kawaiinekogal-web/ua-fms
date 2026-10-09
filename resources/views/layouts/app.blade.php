<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'UA Facility Management System'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    <!-- Load all styles at once to prevent Cloudflare rate limiting -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .footer-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body class="min-h-screen bg-white text-black antialiased">
<div class="flex min-h-screen flex-col">
    @if (!request()->routeIs('login') && !auth()->check())
        @include('layouts.partials.header')
    @endif


    <main class="flex-1">
        @if (session('status'))
            <div class="fms-alert-success mb-4">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="fms-alert-error mb-4">{{ session('error') }}</div>
        @endif

        <div class="@yield('main-content-class', 'mx-auto w-full max-w-6xl px-4 py-6')">
            @yield('content')
        </div>
    </main>

    @if (request()->is('/'))
    <footer class="border-t border-black bg-black text-white">
        <div class="w-full px-12 py-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <!-- Left Column: Logo and Title -->
                <div class="flex items-center gap-3 mr-12">
                    <img src="{{ asset('img/facilities/UA-logo.png') }}" alt="University of Antique Logo" class="footer-logo">
                    <div>
                        <h3 class="text-base font-bold text-white uppercase">UNIVERSITY OF ANTIQUE</h3>
                        <p class="text-xs text-gray-300">Facility Management System</p>
                    </div>
                </div>

                <!-- Center Column: Contact Information -->
                <div class="text-center">
                    <h4 class="text-sm font-bold text-white mb-2 uppercase tracking-wider">CONTACT INFORMATION</h4>
                    <p class="text-xs text-gray-300 leading-relaxed">
                        <span class="block">Sibalom, Antique, Philippines</span>
                        <span class="block">(036) 543-8877</span>
                        <span class="block">fms@ua.edu.ph</span>
                    </p>
                </div>

                <!-- Right Column: System Information -->
                <div>
                    <h4 class="text-sm font-bold text-white mb-2 uppercase tracking-wider">SYSTEM INFORMATION</h4>
                    <div class="text-xs text-gray-300 leading-relaxed space-y-1">
                        <div>
                            <span class="font-medium text-white">System Version:</span>
                            <span class="ml-2">v2.1.0</span>
                        </div>
                        <div>
                            <span class="font-medium text-white">Academic Year:</span>
                            <span class="ml-2">2026–2027</span>
                        </div>
                        <div>
                            <span class="font-medium text-white">GSU-FM Form:</span>
                            <span class="ml-2">GSU-FM-011 Rev.2</span>
                        </div>
                        <div>
                            <span class="font-medium text-white">Last Updated:</span>
                            <span class="ml-2">July 4, 2026</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-700 pt-3 flex justify-between items-center text-[10px] text-gray-400 pr-12">
                <span>University of Antique. All rights reserved.</span>
                <span class="ml-12">GSU Facility Management Office</span>
            </div>
        </div>
    </footer>
    @endif
</div>
</body>
</html>