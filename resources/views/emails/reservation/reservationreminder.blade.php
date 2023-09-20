@component('mail::message')
<u>Station Reservation</u>

Your reservation is about to end. Please be kind enough to upload an image after using the station.

Station: <b>{{ $station->stationName}} </b><br>
Date and Time: <b>{{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d H:i:s') }} 
    ({{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d H:i:s') }}-
    {{ \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d H:i:s') }}) </b>

@component('mail::button', ['url' => route('frontend.stations.station', $station->id)])
    View Reservation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
