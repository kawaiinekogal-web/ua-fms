@extends('layouts.app')

{{-- Removed max-w-[1440px] to MAXIMIZE the UI width across the entire screen --}}
@section('main-class', 'w-full flex-1 px-0 py-0')

@section('content')
<div class="fms-shell">
    @include('layouts.partials.sidebar', [
        'portalTitle' => 'Organization Staff',
        'portalSubtitle' => optional(auth()->user())->organization_name ?? 'Organization not set',
        'sections' => [
            [
                'heading' => 'Overview',
                'links' => [
                    ['label' => 'Dashboard', 'route' => 'org.dashboard'],
                    ['label' => 'My Reservations', 'route' => 'org.bookings.index', 'routes' => 'org.bookings.index'],
                    ['label' => 'Reservation calendar', 'route' => 'org.bookings.calendar'],
                ],
            ],
            [
                'heading' => 'Requests',
                'links' => [
                    ['label' => 'My requests', 'route' => 'org.requests.facilities.index', 'routes' => 'org.requests.facilities.index'],
                    ['label' => 'New request', 'route' => 'org.requests.facilities.create', 'routes' => 'org.requests.facilities.create'],
                ],
            ],
        ],
        'footer' => '<span class="text-black font-medium">Organization</span><br>' . e(optional(auth()->user())->organization_name ?? 'Not set'),
    ])

    <section class="fms-main">
        {{-- Dashboard Topbar --}}
        <div class="flex justify-between items-center border-b border-black pb-3 mb-4 px-0 py-4">
            <h1 class="text-xl font-semibold text-black">Organization Staff Portal</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-black hover:underline flex items-center gap-2">
                    Notifications
                    @if($unreadNotificationsCount ?? 0 > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-black rounded-full">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0 inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-black hover:underline">Logout</button>
                </form>
            </div>
        </div>

        <div class="fms-content-wrapper">
            @yield('org-content')
        </div>
    </section>
</div>


@endsection



