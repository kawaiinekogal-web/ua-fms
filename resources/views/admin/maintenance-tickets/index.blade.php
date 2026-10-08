@extends('layouts.admin')

@section('admin-content')
<div class="space-y-8">
    <header>
        <h1 class="text-2xl font-semibold">Repair &amp; maintenance</h1>
        <p class="mt-1 text-sm text-neutral-600">Review college requests and update their progress.</p>
    </header>

    <section>
        <div class="mb-3 flex items-baseline justify-between">
            <h2 class="text-lg font-semibold">Open requests</h2>
            <span class="text-sm text-neutral-600">{{ $activeTickets->total() }} active</span>
        </div>
        @if ($activeTickets->isNotEmpty())
            <div class="overflow-x-auto border-y border-neutral-300">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-neutral-100 text-xs uppercase text-neutral-600">
                        <tr><th class="px-3 py-3">Code</th><th class="px-3 py-3">Subject</th><th class="px-3 py-3">Sender</th><th class="px-3 py-3">Facility</th><th class="px-3 py-3">Status</th><th class="px-3 py-3">Sent</th><th class="px-3 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @foreach ($activeTickets as $ticket)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-3 font-semibold">{{ $ticket->ticket_code }}</td>
                                <td class="px-3 py-3">{{ $ticket->subject }}</td>
                                <td class="px-3 py-3">{{ $ticket->requester?->name ?? 'Unknown' }}</td>
                                <td class="px-3 py-3">{{ $ticket->facility?->name ?? 'Facility removed' }}</td>
                                <td class="px-3 py-3">{{ ucfirst($ticket->status) }}</td>
                                <td class="whitespace-nowrap px-3 py-3">{{ $ticket->requested_at?->format('M d, Y') ?? $ticket->created_at->format('M d, Y') }}</td>
                                <td class="px-3 py-3"><a class="font-medium underline" href="{{ route('admin.maintenance-tickets.show', $ticket) }}">Review</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $activeTickets->links() }}
        @else
            <p class="border-y border-neutral-300 py-6 text-sm text-neutral-600">No open requests.</p>
        @endif
    </section>

    <section>
        <div class="mb-3 flex items-baseline justify-between">
            <h2 class="text-lg font-semibold">Completed tasks</h2>
            <span class="text-sm text-neutral-600">{{ $completedTickets->total() }} completed</span>
        </div>
        @if ($completedTickets->isNotEmpty())
            <div class="overflow-x-auto border-y border-neutral-300">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-neutral-100 text-xs uppercase text-neutral-600">
                        <tr><th class="px-3 py-3">Code</th><th class="px-3 py-3">Subject</th><th class="px-3 py-3">Sender</th><th class="px-3 py-3">Facility</th><th class="px-3 py-3">Outcome</th><th class="px-3 py-3">Completed</th><th class="px-3 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @foreach ($completedTickets as $ticket)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-3 font-semibold">{{ $ticket->ticket_code }}</td>
                                <td class="px-3 py-3">{{ $ticket->subject }}</td>
                                <td class="px-3 py-3">{{ $ticket->requester?->name ?? 'Unknown' }}</td>
                                <td class="px-3 py-3">{{ $ticket->facility?->name ?? 'Facility removed' }}</td>
                                <td class="px-3 py-3">{{ ucfirst($ticket->status) }}</td>
                                <td class="whitespace-nowrap px-3 py-3">{{ $ticket->completed_at?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-3 py-3"><a class="font-medium underline" href="{{ route('admin.maintenance-tickets.show', $ticket) }}">Review</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $completedTickets->links() }}
        @else
            <p class="border-y border-neutral-300 py-6 text-sm text-neutral-600">No completed tasks yet.</p>
        @endif
    </section>
</div>
@endsection