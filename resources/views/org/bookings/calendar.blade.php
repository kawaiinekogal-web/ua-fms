@extends('layouts.org')

@section('org-content')

<h1 class="fms-page-title mb-6">Reservation Calendar</h1>

@include('public.calendar', ['calendarRoute' => 'org.bookings.index'])

@endsection

