@extends('layouts.guest')

@section('content')
    <div class="fms-card">
        <div class="fms-page-header border-0 pb-0 mb-6">
            <h1 class="fms-page-title">Guest Dashboard</h1>
        </div>
        <p class="mb-6 text-sm text-neutral-600">
            Welcome to the Guest Portal. Submit facility requests with payment and track your reservations.
        </p>

        <!-- Statistics Chart -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- Facilities Requests Chart (3D Pie Chart) -->
            <div class="bg-neutral-50 border border-neutral-200 rounded-lg p-6">
                <h3 class="text-base font-semibold mb-4">My Facilities Requests</h3>
                <div class="relative" style="height: 300px;">
                    <div id="facilitiesChart3D"></div>
                </div>
                <div class="mt-4 text-sm text-neutral-600">
                    <p>Total Requests: <strong>{{ $facilitiesStats['total'] }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Section -->
        @if($upcomingEvents->count() > 0)
        <div class="mb-8">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-neutral-500">Upcoming Events</h2>
            <div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
                <div class="divide-y divide-neutral-200">
                    @foreach($upcomingEvents as $event)
                    <div class="p-4 hover:bg-neutral-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $event->start_time->format('M d, Y') }}
                                    </span>
                                    <span class="text-sm text-neutral-600">
                                        {{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-semibold text-neutral-900 mb-1">
                                    {{ $event->facilities->pluck('name')->join(', ') }}
                                </h4>
                                @if($event->purpose)
                                <p class="text-sm text-neutral-600">{{ Str::limit($event->purpose, 100) }}</p>
                                @endif
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                @if($event->requester_id === auth()->id())
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Your Booking
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="bg-neutral-50 px-4 py-3 text-center border-t border-neutral-200">
                    <a href="{{ route('guest.bookings.calendar') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        View Full Calendar →
                    </a>
                </div>
            </div>
        </div>
        @endif

        <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-neutral-500">Quick access</h2>
        <div class="fms-stat-grid">
            <a href="{{ route('guest.requests.facilities.create') }}" class="fms-stat-card">
                <h3>New Request</h3>
                <p>Submit a facility booking request with payment</p>
            </a>
            <a href="{{ route('guest.requests.facilities.index') }}" class="fms-stat-card">
                <h3>My Requests</h3>
                <p>View and track your facility requests</p>
            </a>
            <a href="{{ route('guest.bookings.calendar') }}" class="fms-stat-card">
                <h3>Calendar</h3>
                <p>View all facility bookings and availability</p>
            </a>
            <a href="{{ route('guest.bookings.index') }}" class="fms-stat-card">
                <h3>My Bookings</h3>
                <p>View your confirmed reservations</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
    <script>
    // Facilities Requests 3D Pie Chart using Plotly
    const facilitiesData = [{
        values: [
            {{ $facilitiesStats['pending'] }},
            {{ $facilitiesStats['pending_payment'] }},
            {{ $facilitiesStats['approved'] }},
            {{ $facilitiesStats['reserved'] }},
            {{ $facilitiesStats['disapproved'] }}
        ],
        labels: ['Pending', 'Pending Payment', 'Approved', 'Reserved', 'Disapproved'],
        type: 'pie',
        marker: {
            colors: [
                '#FCD34D', // Yellow - Pending
                '#FB923C', // Orange - Pending Payment
                '#34D399', // Green - Approved
                '#60A5FA', // Blue - Reserved
                '#F87171'  // Red - Disapproved
            ],
            line: {
                color: '#fff',
                width: 2
            }
        },
        hole: 0,
        pull: [0, 0.05, 0, 0, 0],
        textinfo: 'label+percent',
        textposition: 'outside',
        automargin: true
    }];

    const facilitiesLayout = {
        height: 300,
        margin: { t: 20, b: 20, l: 20, r: 20 },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
        showlegend: true,
        legend: {
            orientation: 'h',
            y: -0.2,
            x: 0.5,
            xanchor: 'center'
        }
    };

    const facilitiesConfig = {
        responsive: true,
        displayModeBar: false
    };

    Plotly.newPlot('facilitiesChart3D', facilitiesData, facilitiesLayout, facilitiesConfig);
    </script>
@endsection
