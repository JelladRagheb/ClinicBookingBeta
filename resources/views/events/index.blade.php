{{-- @extends('layouts.app') --}}
<x-app-layout>
    @yield('content')
    <h1>
        Google Calendar Events
    </h1>
    @if (count($events) > 0)
        <ul>
            @foreach ($events as $event)
                <li>{{ $event->getSummary() }} {{ $event->getStart()->getDateTime() }}</li>
            @endforeach
        </ul>
    @else
        <p>No events found</p>
    @endif

    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <input type="text" name="summary" placeholder="Event Title">
        <input type="datetime-local" name="start">
        <input type="datetime-local" name="end">
        <button type="submit">Add Event</button>
    </form>
    {{-- @endsection --}}
</x-app-layout>
