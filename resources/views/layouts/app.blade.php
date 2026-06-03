<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'UA Facility Management System'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/ua-public-fix.css', 'resources/js/app.js'])
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

    <footer class="border-t border-black bg-white">
        <div class="mx-auto w-full max-w-[1440px] px-6 py-4 text-xs text-neutral-600">
            University of Antique · GSU Facility & Equipment Management System
        </div>
    </footer>
</div>
</body>
</html>