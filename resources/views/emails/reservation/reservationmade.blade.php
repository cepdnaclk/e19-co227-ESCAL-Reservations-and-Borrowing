@component('mail::message')
<u>Station Reservation</u>

{{ $reserver->name}} made a reservation.

Group members: <b>{{ $booking->E_numbers}}</b><br>
Station: <b>{{ $station->stationName}} </b><br>
Date and Time: <b>{{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d H:i:s') }} 
    ({{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d H:i:s') }}-
    {{ \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d H:i:s') }}) </b>

Approval State: Pending

@component('mail::button', ['url' => route('frontend.stations.station', $station->id)])
    View Station
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
