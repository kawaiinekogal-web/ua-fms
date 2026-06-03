{{-- resources/views/public/calendar.blade.php --}}
{{-- Used as a partial inside welcome.blade.php and portal calendars. Expects $currentMonth, $days, etc. --}}

{{-- CALENDAR SECTION (partial: no layout, no html/head/body, no @extends) --}}
@php
    $baseRoute = $calendarRoute ?? 'home';
@endphp

<div class="calendar-wrapper" id="calendar">

  <div class="cal-inner">
    <div class="section-head">
      <h2>Reservation Booking Schedule</h2>
      <p>Public calendar of approved and scheduled facility reservations. All times are in Philippine Standard Time.</p>
    </div>

    <div class="cal-wrap">
      {{-- Calendar Grid Component --}}
      <div class="cal-card">
        <div class="cal-header">
          @php
            $monthName = $currentMonth->format('F Y');
            $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
            $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
          @endphp
          <span class="cal-month">{{ $monthName }}</span>
          <div class="cal-nav-pair">

            {{-- Navigation uses dynamic route parameter --}}
            <a href="{{ route($baseRoute, ['month' => $prevMonth]) }}#calendar" class="cal-nav-btn" title="Previous month">&#8249;</a>
            <a href="{{ route($baseRoute, ['month' => $nextMonth]) }}#calendar" class="cal-nav-btn" title="Next month">&#8250;</a>

          </div>
        </div>
        <div class="cal-grid-head">
          @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
            <div class="cal-dh">{{ $d }}</div>
          @endforeach
        </div>
        <div class="cal-days">
          @php
            $startOfMonth  = $currentMonth->copy()->startOfMonth();
            $endOfMonth    = $currentMonth->copy()->endOfMonth();
            $startOfCal    = $startOfMonth->copy()->startOfWeek(0);
            $endOfCal      = $endOfMonth->copy()->endOfWeek(6);
            $today         = \Carbon\Carbon::today();
            $cursor        = $startOfCal->copy();
          @endphp
          @while($cursor->lte($endOfCal))
            @php
              $dateKey   = $cursor->format('Y-m-d');
              $isOther   = !$cursor->isSameMonth($currentMonth);
              $isToday   = $cursor->isSameDay($today);
              $hasEvents = isset($days[$dateKey]) && count($days[$dateKey]) > 0;
              $classes   = 'cal-day';
              if($isOther)   $classes .= ' other';
              if($isToday)   $classes .= ' today';
              if($hasEvents && !$isOther) $classes .= ' has-event';
            @endphp
            <div class="cal-day" @if($hasEvents && !$isOther) title="{{ count($days[$dateKey]) }} reservation(s)" @endif>
              {{ $cursor->day }}
            </div>
            @php $cursor->addDay() @endphp
          @endwhile
        </div>
      </div>

      {{-- Events Sidebar Matrix Component --}}
      <div class="events-card">
        <div class="events-header">
          <h4>Upcoming Reservations  </h4>
        </div>
        <div class="events-list">
          @php
            $upcoming = [];
            foreach($days as $dateStr => $bookings) {
              try {
                $dt = \Carbon\Carbon::parse($dateStr);
                if($dt->gte($today)) {
                  foreach($bookings as $bk) {
                    $upcoming[] = ['date' => $dt, 'booking' => $bk];
                  }
                }
              } catch(\Exception $e) {}
            }
            usort($upcoming, fn($a,$b) => $a['date']->timestamp - $b['date']->timestamp);
            $upcoming = array_slice($upcoming, 0, 6);
          @endphp

          @forelse($upcoming as $item)
            @php $bk = $item['booking']; @endphp
            <div class="event-row">
              <div class="event-date-badge">{{ $item['date']->format('M j') }}</div>
              <div class="event-info">
                <div class="event-facility">
                  {{ $bk->facilities->pluck('name')->join(', ') ?: 'Facility' }}
                </div>
                <div class="event-time">
                  {{ $bk->start_time ? $bk->start_time->format('g:i A') : '' }}
                  @if($bk->end_time) – {{ $bk->end_time->format('g:i A') }} @endif
                </div>
              </div>
            </div>
          @empty
            <div class="empty-state">No upcoming reservations for this period.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>



