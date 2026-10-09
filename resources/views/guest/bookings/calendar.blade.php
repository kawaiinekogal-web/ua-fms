@extends('layouts.guest')

@section('content')

<div class="cal-section">
    <div class="cal-inner">
        <div class="mb-4">
            <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium transition">
                ← Back to Dashboard
            </a>
        </div>

        <div class="section-head" style="margin-bottom: 32px;">
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; font-weight: 400; margin-bottom: 10px;">Reservation Calendar</h2>
            <p style="font-size: 14px; color: #4D4D4D; line-height: 1.75;">Public calendar of approved and scheduled facility reservations. All times are in Philippine Standard Time.</p>
        </div>

        @include('public.calendar', ['calendarRoute' => 'guest.bookings.calendar'])
    </div>
</div>

@endsection
