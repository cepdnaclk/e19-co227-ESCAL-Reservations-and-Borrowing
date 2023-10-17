@extends('backend.layouts.app')

@section('title', __('Reservation'))

@section('breadcrumb-links')
    @include('backend.reservation.includes.breadcrumb-links')
@endsection

@section('content')
    <div>
        <x-backend.card>
            <x-slot name="header">
                Reservation : Show |
                @if($reservation->res_info() != null)
                    {{ $reservation->res_info['name'] }}
                @endif
            </x-slot>

            <x-slot name="body">
                <div class="container pb-2 d-inline-flex">
                    <div class="d-flex">
                        <h4>
                            @if($reservation->res_info() != null)
                                {{ $reservation->res_info['name'] }}
                            @endif
                        </h4>
                    </div>
                    <div class="d-flex px-0 mt-0 mb-0 ml-auto">
                        <div class="btn-group" role="group" aria-label="Modify Buttons">
                            <a href="{{ route('admin.components.reservation.edit', $reservation)}}"
                               class="btn btn-primary btn-xs me-2"><i class="fa fa-check" title="Approve"></i>
                                Approve
                            </a>
                        </div>
                    </div>
                </div>
                <table class="table">

                    <tr>
                        <td>User ID</td>
                        <td>{{ $reservation->user_id }}</td>
                    </tr>

                    <tr>
                        <td>User Email</td>
                        <td>
                            @if($reservation->res_info() != null)
                                {{ $reservation->res_info['email'] }}
                            @endif
                        </td>

                    </tr>

                    <tr>
                        <td>Items</td>
                        <td>
                            @foreach ($reservation->componentItems as $item)
                                <table class="table">
                                    <tr>
                                        <td>Component Id</td>
                                        <td>{{ $item->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Title</td>
                                        <td>{{ $item->title }}</td>
                                    </tr>
                                    <tr>
                                        <td>Brand</td>
                                        <td>{{ $item->brand }}</td>
                                    </tr>
                                </table>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td>Ordered Date</td>
                        <td>{{ $reservation->ordered_date }}</td>
                    </tr>

                    <tr>
                        <td>Picked Date</td>
                        <td>{{ $reservation->picked_date }}</td>
                    </tr>

                    <tr>
                        <td>Due Date</td>
                        <td>{{ $reservation->due_date_to_return }}</td>
                    </tr>

                    <tr>
                        <td>Returned Date</td>
                        <td>{{ $reservation->returned_date }}</td>
                    </tr>

                    <tr>
                        <td>Comments</td>
                        <td>{{ $reservation->comments }}</td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td>
                            @if($reservation->status == "ready")
                                <span class="text-success">Ready</span>
                            @elseif($reservation->status == "picked")
                                <span class="text-success">Picked</span>
                            @elseif($reservation->status == "delivered")
                                <span class="text-success">Delivered</span>
                            @elseif($reservation->status == "rejected")
                                <span class="text-danger">Rejected</span>                                        
                            @else
                                <span class="text-primary">Pending</span>                                        
                            @endif
                        </td>                        
                    </tr>

                </table>
            </x-slot>
        </x-backend.card>
    </div>
@endsection