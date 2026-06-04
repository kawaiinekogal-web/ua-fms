{{-- resources/views/public/calendar.blade.php --}}
{{-- Used as a partial inside welcome.blade.php and portal calendars. Expects $currentMonth, $days, etc. --}}

{{-- CALENDAR SECTION (partial: no layout, no html/head/body, no @extends) --}}
@php
    $baseRoute = $calendarRoute ?? 'home';
    $today     = \Carbon\Carbon::now(); // uses app timezone (Asia/Manila)
@endphp

<style>
  #cal-events-sidebar,
  #cal-events-sidebar div,
  #cal-events-sidebar span,
  #cal-events-sidebar h4,
  #cal-events-sidebar p {
    color: #111111 !important;
    background-color: #ffffff !important;
  }
  #cal-events-sidebar .event-date-badge {
    background-color: #E0E0E0 !important;
    color: #111111 !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    padding: 3px 8px !important;
    border-radius: 4px !important;
    flex-shrink: 0 !important;
    min-width: 32px !important;
    text-align: center !important;
    white-space: nowrap !important;
    margin-top: 1px !important;
  }
  #cal-events-sidebar .events-card {
    border: 1px solid #E6E6E6 !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    box-shadow: 0 1px 2px rgba(0,0,0,.05) !important;
  }
  #cal-events-sidebar .events-header {
    padding: 14px 18px !important;
    border-bottom: 1px solid #E6E6E6 !important;
  }
  #cal-events-sidebar .events-header h4 {
    font-size: 13px !important;
    font-weight: 600 !important;
  }
  #cal-events-sidebar .events-list {
    padding: 12px 16px 20px !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 10px !important;
  }
  #cal-events-sidebar .event-row {
    display: flex !important;
    gap: 10px !important;
    align-items: flex-start !important;
    padding-bottom: 10px !important;
    border-bottom: 1px solid #E6E6E6 !important;
  }
  #cal-events-sidebar .event-row:last-child {
    border-bottom: none !important;
    padding-bottom: 0 !important;
  }
  #cal-events-sidebar .event-facility {
    font-size: 12px !important;
    font-weight: 600 !important;
    margin-bottom: 2px !important;
  }
  #cal-events-sidebar .event-time {
    font-size: 11px !important;
    color: #4D4D4D !important;
  }
  #cal-events-sidebar .event-purpose {
    font-size: 11px !important;
    color: #808080 !important;
    margin-top: 2px !important;
  }
  #cal-events-sidebar .empty-state {
    padding: 24px 16px !important;
    text-align: center !important;
    font-size: 12px !important;
    color: #808080 !important;
  }
</style>

<div class="calendar-wrapper" id="calendar">

  <div class="cal-inner">
    <div class="section-head">
      <h2>Reservation Schedule</h2>
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
            <div class="{{ $classes }}" @if($hasEvents && !$isOther) title="{{ count($days[$dateKey]) }} reservation(s)" @endif>
              {{ $cursor->day }}
            </div>
            @php $cursor->addDay() @endphp
          @endwhile
        </div>
      </div>

      {{-- Events Sidebar --}}
      <div class="events-col" id="cal-events-sidebar">
        @php
          $isAdmin = auth()->check() && auth()->user()->isAdmin();

          // Today's reservations
          $todayKey = $today->toDateString();
          $currentReservations = collect($days[$todayKey] ?? [])->sortBy('start_time')->values();

          // Upcoming reservations (today onwards, capped at 6)
          $upcoming = [];
          foreach ($days as $dateStr => $bookings) {
            try {
              $dt = \Carbon\Carbon::parse($dateStr);
              if ($dt->gte($today)) {
                foreach ($bookings as $bk) {
                  $upcoming[] = ['date' => $dt, 'booking' => $bk];
                }
              }
            } catch (\Exception $e) {}
          }
          usort($upcoming, fn($a, $b) => $a['date']->timestamp - $b['date']->timestamp);
          $upcoming = array_slice($upcoming, 0, 6);
        @endphp

        {{-- CARD 1: CURRENT RESERVATIONS (Today) --}}
        <div class="events-card">
          <div class="events-header">
            <h4>Current Reservations (Today)</h4>
          </div>
          <div class="events-list">
            @if($currentReservations->isEmpty())
              <div class="empty-state">No reservations scheduled for today.</div>
            @else
              @foreach($currentReservations as $bk)
                <div class="event-row">
                  <div class="event-date-badge">{{ $today->format('M j') }}</div>
                  <div class="event-info">
                    <div class="event-facility">
                      {{ $bk->facilities->pluck('name')->join(', ') ?: 'Facility' }}
                    </div>
                    <div class="event-time">
                      {{ $bk->start_time ? $bk->start_time->format('g:i A') : '' }}
                      @if($bk->end_time) – {{ $bk->end_time->format('g:i A') }} @endif
                    </div>
                    @if($isAdmin)
                      <div class="event-purpose">{{ $bk->purpose ?? 'No purpose provided.' }}</div>
                      <div class="event-purpose">
                        Requester: {{ optional($bk->requester)->name ?? 'Unknown' }}
                        ({{ $bk->requester_type ?? 'N/A' }} – {{ $bk->requester_unit ?? 'N/A' }})
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>

        {{-- CARD 2: UPCOMING RESERVATIONS --}}
        <div class="events-card">
          <div class="events-header">
            <h4>Upcoming Reservations</h4>
          </div>
          <div class="events-list">
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
                  @if($isAdmin)
                    <div class="event-purpose">{{ $bk->purpose ?? 'No purpose provided.' }}</div>
                    <div class="event-purpose">
                      Requester: {{ optional($bk->requester)->name ?? 'Unknown' }}
                      ({{ $bk->requester_type ?? 'N/A' }} – {{ $bk->requester_unit ?? 'N/A' }})
                    </div>
                  @endif
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
</div>



