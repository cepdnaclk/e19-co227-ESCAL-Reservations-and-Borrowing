@component('mail::message')
<u>Component Reservation</u>

Name: <b>{{ $reserver->name}}</b><br>
Items: <b>
    @foreach ($order->componentItems as $item)
        {{$item->title}} ,
    @endforeach
    </b><br>
Date and Time: <b>
    {{ \Carbon\Carbon::parse($order->ordered_date)->format('Y-m-d H:i:s') }}</b><br>
Comments: <b>{{$order->comments}}</b><br>

Approval State: {{$order->status}}

@component('mail::button', ['url' => route('frontend.stations.station', $order->id)])
    View Reservation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
