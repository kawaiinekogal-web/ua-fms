@extends('layouts.college')

@section('college-content')
<div class="fms-card">
    <div class="fms-page-header">
        <div>
            <h1 class="fms-page-title">Repair &amp; maintenance requests</h1>
            <p class="text-sm text-neutral-600">Track requests sent to the administrators.</p>
        </div>
        <a href="{{ route('college.maintenance-tickets.create') }}" class="fms-btn-primary">New request</a>
    </div>

    @if ($tickets->isNotEmpty())
        <div class="fms-table-wrap">
            <table class="fms-table">
                <thead>
                    <tr>
                        <th>Tracking code</th>
                        <th>Subject</th>
                        <th>Facility</th>
                        <th>Status</th>
                        <th>Sent</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td><strong>{{ $ticket->ticket_code }}</strong></td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ $ticket->facility?->name ?? 'Facility removed' }}</td>
                            <td><span class="fms-badge">{{ ucfirst($ticket->status) }}</span></td>
                            <td>{{ $ticket->requested_at?->format('M d, Y') ?? $ticket->created_at->format('M d, Y') }}</td>
                            <td><a href="{{ route('college.maintenance-tickets.show', $ticket) }}" class="fms-link">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $tickets->links() }}
    @else
        <p class="py-10 text-center text-neutral-600">You have not sent any repair or maintenance requests.</p>
    @endif
</div>
@endsection