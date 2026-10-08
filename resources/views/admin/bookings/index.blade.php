@extends('layouts.admin')

@section('admin-content')
<div class="fms-card">
    <div class="fms-page-header">
        <h1 class="fms-page-title">Reservations (GSU overview)</h1>
        <a href="{{ route('admin.bookings.create-direct') }}" class="fms-button-primary">
            <i class="fas fa-plus mr-2"></i> Create Direct Booking
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="mb-6 p-4 bg-neutral-50 rounded">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            <!-- Search by Requester or Purpose -->
            <div>
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    class="w-full px-3 py-2 border border-neutral-300 rounded text-sm" 
                    placeholder="Requester, facility, or purpose..." 
                    value="{{ request('search') }}"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-neutral-300 rounded text-sm">
                    <option value="">All Statuses</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Facility Filter -->
            <div>
                <label for="facility_id" class="block text-sm font-medium text-neutral-700 mb-1">Facility</label>
                <select id="facility_id" name="facility_id" class="w-full px-3 py-2 border border-neutral-300 rounded text-sm">
                    <option value="">All Facilities</option>
                    @foreach ($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label for="month" class="block text-sm font-medium text-neutral-700 mb-1">Month</label>
                <input 
                    type="month" 
                    id="month" 
                    name="month" 
                    class="w-full px-3 py-2 border border-neutral-300 rounded text-sm" 
                    value="{{ request('month') }}"
                />
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 whitespace-nowrap">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 bg-neutral-400 text-white text-sm rounded hover:bg-neutral-500 whitespace-nowrap">Clear</a>
            </div>
        </div>
    </form>

    @if($bookings->count())
        <div class="fms-table-wrap">
            <table class="fms-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Requester</th>
                        <th>Facility</th>
                        <th>When</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>
                                {{ optional($booking->requester)->name ?? 'Unknown' }}<br>
                                <span class="text-xs text-neutral-500">
                                    {{ $booking->requester_type }} – {{ $booking->requester_unit ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($booking->facilities->isNotEmpty())
                                    {{ $booking->facilities->pluck('name')->join(', ') }}
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td>
                                {{ $booking->start_time?->format('M d, Y H:i') }} –
                                {{ $booking->end_time?->format('H:i') }}
                            </td>
                            <td><span class="fms-badge">{{ ucfirst($booking->status) }}</span></td>
                            <td>
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="fms-link">
                                    View / Modify
                                </a>
                                @php
                                    $submissionId = $booking->additional_details['form_submission_id'] ?? null;
                                @endphp
                                @if($submissionId)
                                    <a href="{{ route('admin.forms.facilities.pdf', $submissionId) }}" class="fms-btn fms-btn-secondary ml-3" style="font-size:12px;padding:4px 10px;">
                                        Generate PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <p class="text-sm text-neutral-600">No bookings yet.</p>
    @endif
</div>
@endsection