<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'UA-FMS') }} - Guest</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('guest.dashboard') }}" class="text-xl font-bold">
                        UA-FMS Guest
                    </a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('guest.dashboard') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 {{ request()->routeIs('guest.dashboard') ? 'bg-blue-800' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('guest.requests.facilities.create') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 {{ request()->routeIs('guest.requests.facilities.create') ? 'bg-blue-800' : '' }}">
                            New Request
                        </a>
                        <a href="{{ route('guest.requests.facilities.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 {{ request()->routeIs('guest.requests.facilities.index') ? 'bg-blue-800' : '' }}">
                            My Requests
                        </a>
                        <a href="{{ route('guest.bookings.calendar') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 {{ request()->routeIs('guest.bookings.calendar') ? 'bg-blue-800' : '' }}">
                            Calendar
                        </a>
                        <a href="{{ route('guest.bookings.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 {{ request()->routeIs('guest.bookings.index') ? 'bg-blue-800' : '' }}">
                            My Bookings
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 rounded-md text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
