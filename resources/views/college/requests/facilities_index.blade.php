@extends('layouts.college')

@section('college-content')
<div class="fms-card">
    <div class="fms-page-header">
        <h1 class="fms-page-title">My Facilities  Approve Requests</h1>
        <a href="{{ route('college.requests.facilities.create') }}" class="fms-btn-primary">New Request</a>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('college.requests.index') }}" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            {{-- Control Number --}}
            <div>
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">
                    Control Number
                </label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    class="fms-input"
                    placeholder="BKG-..."
                    value="{{ request('search') }}"
                >
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">
                    Status
                </label>
                <select id="status" name="status" class="fms-input">
                    <option value="">All</option>
                    @foreach (['pending', 'approved', 'reserved', 'disapproved', 'cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ ucfirst($st === 'reserved' ? 'Reserved' : $st) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Activity Date --}}
            <div>
                <label for="activity_date" class="block text-sm font-medium text-neutral-700 mb-1">
                    Activity Date
                </label>
                <input
                    type="date"
                    id="activity_date"
                    name="activity_date"
                    class="fms-input"
                    value="{{ request('activity_date') }}"
                >
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('college.requests.index') }}" class="fms-btn-secondary">
                Clear
            </a>
            <button type="submit" class="fms-btn-primary">
                Apply Filters
            </button>
        </div>
    </form>

    @if ($submissions->count() > 0)
        <div class="fms-table-wrap">
            <table class="fms-table">
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Date Requested</th>
                        <th>Activity Date</th>
                        <th>Time</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($submissions as $submission)
                        <tr>
                            <td><strong># Facilities Request #{{ $submission->id }}</strong></td>
                            <td>{{ $submission->created_at->format('M d, Y') }}</td>
                            <td>{{ $submission->payload['date_activity'] ?? '-' }}</td>
                            <td>
                                {{ $submission->payload['time_range']['start'] ?? '-' }} -
                                {{ $submission->payload['time_range']['end'] ?? '-' }}
                            </td>
                            <td>{{ Str::limit($submission->payload['purpose'] ?? '-', 50) }}</td>
                            <td>
                                <span class="fms-badge">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('college.requests.facilities.show', $submission) }}" class="fms-link">View</a>
                                    @if ($submission->status === 'pending')
                                        <form method="POST" action="{{ route('college.requests.facilities.cancel', $submission) }}" style="display:inline;" onsubmit="return confirm('Cancel this request?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Cancel</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $submissions->links() }}
    @else
        <div class="py-12 text-center">
            <p class="mb-4 text-neutral-600">No facilities  Approve Requests found.</p>
            <a href="{{ route('college.requests.facilities.create') }}" class="fms-btn-primary">Submit Your Request</a>
        </div>
    @endif
</div>
@endsection
