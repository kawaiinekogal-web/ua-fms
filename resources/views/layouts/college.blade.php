@extends('layouts.app')

{{-- Removed max-w-[1440px] to MAXIMIZE the UI width across the entire screen --}}
@section('main-class', 'w-full flex-1 px-0 py-0')

@section('content')
<div class="fms-shell">
    @include('layouts.partials.sidebar', [
        'portalTitle' => 'College Staff',
        'portalSubtitle' => optional(auth()->user())->college_name ?? 'College not set',
        'sections' => [
            [
                'heading' => 'Overview',
                'links' => [
                    ['label' => 'Dashboard', 'route' => 'college.dashboard'],
                    ['label' => 'Reservation calendar', 'route' => 'college.calendar'],
                    ['label' => 'Reservations', 'route' => 'college.bookings.index', 'routes' => 'college.bookings.*'],
                ],
            ],
            [
                'heading' => 'Requests',
                'links' => [
                    ['label' => 'My requests', 'route' => 'college.requests.index', 'routes' => 'college.requests.index'],
                    ['label' => 'New request', 'route' => 'college.requests.facilities.create', 'routes' => 'college.requests.facilities.create'],
                    ['label' => 'Repair & maintenance', 'route' => 'college.maintenance-tickets.index', 'routes' => 'college.maintenance-tickets.*'],
                ],
            ],
        ],
        'footer' => '<span class="text-black font-medium">Signed in as</span><br>' . e(optional(auth()->user())->name),
    ])

    <section class="fms-main">
        {{-- Dashboard Topbar --}}
        <div class="flex justify-between items-center border-b border-black pb-3 mb-4 px-0 py-4">
            <h1 class="text-xl font-semibold text-black">College Staff Portal</h1>
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
            @yield('college-content')
        </div>
    </section>
</div>


@endsection


