@extends('layouts.admin')

@section('admin-content')
<div class="bg-white rounded shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Utilization Request Approvals</h1>
            <p class="text-xs text-gray-500 mt-1">Review and approve facilities  Approve Requests from colleges and organizations</p>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-50 text-green-800 border border-green-200 rounded">
            {{ session('status') }}
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.forms.facilities.index') }}" class="mb-6 p-4 bg-gray-50 rounded">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            <!-- Search by Control Number -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Control Number</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                    placeholder="BKG-123..." 
                    value="{{ request('search') }}"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="disapproved" {{ request('status') === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                </select>
            </div>

            <!-- Facility Filter -->
            <div>
                <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-1">Facility</label>
                <select id="facility_id" name="facility_id" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
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
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Activity Month</label>
                <input 
                    type="month" 
                    id="month" 
                    name="month" 
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                    value="{{ request('month') }}"
                />
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Filter</button>
                <a href="{{ route('admin.forms.facilities.index') }}" class="px-4 py-2 bg-gray-400 text-white text-sm rounded hover:bg-gray-500">Clear</a>
            </div>
        </div>
    </form>

    @if($submissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Control No.</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($submissions as $submission)
                        <tr>
                            <td class="px-4 py-2">{{ $submission->payload['control_no'] ?? 'Pending' }}</td>
                            <td class="px-4 py-2">
                                {{ optional($submission->requester)->name ?? 'Unknown' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $submission->requester_unit ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
@if($submission->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($submission->status === 'reserved') bg-blue-100 text-blue-800
                                    @elseif($submission->status === 'approved') bg-green-100 text-green-800
                                    @elseif($submission->status === 'disapproved') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
{{ $submission->status === 'reserved' ? 'Reserved' : ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                {{ $submission->created_at?->format('Y-m-d H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.forms.facilities.show', $submission) }}" class="text-blue-600 hover:text-blue-900">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $submissions->links() }}
        </div>
    @else
        <p class="text-gray-600">No facilities  Approve Requests found.</p>
    @endif
</div>
@endsection