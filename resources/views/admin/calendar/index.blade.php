@extends('layouts.admin')

@section('admin-content')
@php
    use Carbon\Carbon;
    $monthLabel = $currentMonth->format('F Y');
@endphp

<div class="fms-card">
    <div class="fms-page-header border-0 pb-0 mb-4">
        <div>
            <h1 class="fms-page-title">Reservation Calendar</h1>
            <p class="text-xs text-neutral-600">
                Month view of all reserved and rescheduled reservations with facility and purpose details.
            </p>
        </div>
    </div>

    {{-- Shared monochrome calendar partial; navigation stays on /admin/calendar --}}
    @include('public.calendar', ['calendarRoute' => 'admin.calendar'])

    <h2 class="mt-8 mb-2 text-sm font-semibold uppercase tracking-widest text-neutral-500">
        Facility reservation overview ({{ $monthLabel }})
    </h2>

    <div class="fms-table-wrap">
        <table class="fms-table">
            <thead>
                <tr>
                    <th>Facility</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Reservations this month</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($facilityCounts as $item)
                    @php
                        $facility = $item['facility'];
                        $count    = $item['count'];
                        $status   = $facility->availability_status ?? 'available';
                    @endphp
                    <tr>
                        <td>{{ $facility->name }}</td>
                        <td>
                            @if ($facility->owner_type === 'gsu')
                                GSU
                            @elseif ($facility->owner_type === 'college')
                                College ({{ $facility->owner_college ?? 'N/A' }})
                            @elseif ($facility->owner_type === 'org')
                                Organization
                            @else
                                {{ ucfirst($facility->owner_type ?? 'Unknown') }}
                            @endif
                        </td>
                        <td><span class="fms-badge">{{ ucfirst($status) }}</span></td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
