@extends('layouts.college')

@section('college-content')

<h1 class="fms-page-title mb-6">Reservation Calendar</h1>

@include('public.calendar', ['calendarRoute' => 'college.calendar'])

@endsection
