@extends('layouts.guest')

@section('content')
<div class="fms-card">
    <div class="mb-4">
        <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium transition">
            ← Back to Dashboard
        </a>
    </div>

    <div class="fms-page-header">
        <h1 class="fms-page-title">My Reservations</h1>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('guest.bookings.index') }}" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Search by Facility or Purpose -->
            <div>
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    class="fms-input"
                    placeholder="Facility or purpose..."
                    value="{{ request('search') }}"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                <select id="status" name="status" class="fms-input">
                    <option value="">All Statuses</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label for="month" class="block text-sm font-medium text-neutral-700 mb-1">Month</label>
                <input
                    type="month"
                    id="month"
                    name="month"
                    class="fms-input"
                    value="{{ request('month') }}"
                />
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="fms-btn-primary whitespace-nowrap">Filter</button>
                <a href="{{ route('guest.bookings.index') }}" class="fms-btn-secondary whitespace-nowrap">Clear</a>
            </div>
        </div>
    </form>

    @if ($bookings->count() > 0)
        <div class="fms-table-wrap">
            <table class="fms-table">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Facility</th>
                        <th>Purpose</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_code }}</strong></td>
                            <td>{{ $booking->start_time->format('M d, Y') }}</td>
                            <td>{{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}</td>
                            <td>{{ $booking->facilities->pluck('name')->join(', ') ?: 'Unknown' }}</td>
                            <td>{{ Str::limit($booking->purpose ?? '-', 50) }}</td>
                            <td>
                                <span class="fms-badge
                                    @if($booking->status === 'reserved') bg-blue-100 text-blue-800
                                    @elseif($booking->status === 'rescheduled') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $bookings->links() }}
    @else
        <div class="py-12 text-center">
            <p class="text-neutral-600">No reservations found. Once your facility requests are approved, they will appear here.</p>
        </div>
    @endif
</div>
@endsection
