@extends('layouts.org')

@section('org-content')
<div class="fms-card max-w-4xl">
    <div class="fms-page-header">
        <div>
            <p class="text-sm text-neutral-600">Tracking code</p>
            <h1 class="fms-page-title">{{ $ticket->ticket_code }}</h1>
        </div>
        <a href="{{ route('org.maintenance-tickets.index') }}" class="fms-btn-secondary">All requests</a>
    </div>

    <dl class="grid grid-cols-1 gap-4 border-y border-neutral-200 py-5 sm:grid-cols-2">
        <div><dt class="text-sm text-neutral-600">Status</dt><dd class="mt-1 font-semibold">{{ ucfirst($ticket->status) }}</dd></div>
        <div><dt class="text-sm text-neutral-600">Facility</dt><dd class="mt-1 font-semibold">{{ $ticket->facility?->name ?? 'Facility removed' }}</dd></div>
        <div><dt class="text-sm text-neutral-600">Sent</dt><dd class="mt-1">{{ $ticket->requested_at?->format('M d, Y h:i A') ?? $ticket->created_at->format('M d, Y h:i A') }}</dd></div>
        @if ($ticket->completed_at)
            <div><dt class="text-sm text-neutral-600">Completed</dt><dd class="mt-1">{{ $ticket->completed_at->format('M d, Y h:i A') }}</dd></div>
        @endif
    </dl>

    <section class="py-5">
        <h2 class="text-sm font-semibold text-neutral-600">Subject</h2>
        <p class="mt-1 text-lg font-semibold">{{ $ticket->subject }}</p>
        <h2 class="mt-5 text-sm font-semibold text-neutral-600">Message</h2>
        <p class="mt-1 whitespace-pre-line">{{ $ticket->issue_description }}</p>
    </section>

    @if ($ticket->admin_remarks)
        <section class="border-t border-neutral-200 py-5">
            <h2 class="text-sm font-semibold text-neutral-600">Administrator update</h2>
            <p class="mt-1 whitespace-pre-line">{{ $ticket->admin_remarks }}</p>
        </section>
    @endif
</div>
@endsection
