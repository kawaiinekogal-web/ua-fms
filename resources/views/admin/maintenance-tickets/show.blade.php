@extends('layouts.admin')

@section('admin-content')
<div class="max-w-4xl space-y-6">
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm text-neutral-600">Repair &amp; maintenance ticket</p>
            <h1 class="text-2xl font-semibold">{{ $ticket->ticket_code }}</h1>
        </div>
        <a href="{{ route('admin.maintenance-tickets.index') }}" class="text-sm font-medium underline">Back to tickets</a>
    </header>

    <section class="grid grid-cols-1 gap-4 border-y border-neutral-300 py-5 sm:grid-cols-2">
        <div><p class="text-sm text-neutral-600">Sender</p><p class="mt-1 font-medium">{{ $ticket->requester?->name ?? 'Unknown' }}</p><p class="text-sm">{{ $ticket->requester?->email }}</p></div>
        <div><p class="text-sm text-neutral-600">College</p><p class="mt-1 font-medium">{{ $ticket->requester?->college_name ?? 'Not specified' }}</p></div>
        <div><p class="text-sm text-neutral-600">Facility</p><p class="mt-1 font-medium">{{ $ticket->facility?->name ?? 'Facility removed' }}</p></div>
        <div><p class="text-sm text-neutral-600">Submitted</p><p class="mt-1">{{ $ticket->requested_at?->format('M d, Y h:i A') ?? $ticket->created_at->format('M d, Y h:i A') }}</p></div>
    </section>

    <section>
        <h2 class="text-sm font-semibold text-neutral-600">Subject</h2>
        <p class="mt-1 text-lg font-semibold">{{ $ticket->subject }}</p>
        <h2 class="mt-5 text-sm font-semibold text-neutral-600">Message</h2>
        <p class="mt-1 whitespace-pre-line">{{ $ticket->issue_description }}</p>
    </section>

    <form method="POST" action="{{ route('admin.maintenance-tickets.update', $ticket) }}" class="space-y-4 border-t border-neutral-300 pt-5">
        @csrf
        @method('PUT')
        <h2 class="text-lg font-semibold">Update ticket</h2>
        @if ($errors->any())
            <div class="border border-red-300 bg-red-50 p-3 text-sm text-red-800">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <div>
            <label for="status" class="mb-1 block text-sm font-medium">Current status</label>
            <select id="status" name="status" required class="w-full max-w-sm border border-neutral-400 bg-white px-3 py-2">
                <option value="notified" disabled @selected($ticket->status === 'notified')>Notified</option>
                @foreach (['pending' => 'Pending', 'ongoing' => 'Ongoing', 'success' => 'Success', 'failed' => 'Failed'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $ticket->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="admin_remarks" class="mb-1 block text-sm font-medium">Update for sender</label>
            <textarea id="admin_remarks" name="admin_remarks" rows="4" maxlength="5000" class="w-full border border-neutral-400 px-3 py-2">{{ old('admin_remarks', $ticket->admin_remarks) }}</textarea>
        </div>
        <button type="submit" class="fms-btn-primary">Save update</button>
    </form>
</div>
@endsection