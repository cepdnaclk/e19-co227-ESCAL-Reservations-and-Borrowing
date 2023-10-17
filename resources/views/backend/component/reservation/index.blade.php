@extends('backend.layouts.app')

@section('title', __('component'))

@section('breadcrumb-links')
    @include('backend.component.includes.breadcrumb-links')
@endsection

@section('content')
    <div>
        <x-backend.card>
            <x-slot name="header">
                Reservation
            </x-slot>

            <x-slot name="body">

                @if (session('Success'))
                    <div class="alert alert-success">
                        {{ session('Success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="container table-responsive pt-3">
                    <table class="table table-striped">
                        <tr>
                            <th>User Name</th>
                            <th>Ordered Date</th>
                            <th>Picked Date</th>
                            <th>Due Date to Return</th>
                            <th>Returned Date</th>
                            <th>E Number</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>

                        @foreach($reservation as $res)
                            <tr>
                           

                                <td>
                                    @if($res->res_info() != null)
                                        {{ $res->res_info['name'] }}
                                    @endif
                                </td>

                                <td>{{ $res->ordered_date }}</td>
                                <td>{{ $res->picked_date }}</td>
                                <td>{{ $res->due_date_to_return }}</td>
                                <td>{{ $res->returned_date }}</td>
                                <td>
                                    @if($res->enum_info() != null)
                                        {{ $res->enum_info['email'] }}
                                    @endif
                                </td>
                                <td>
                                    @if($res->status == "ready")
                                        <span class="text-success">Ready</span>
                                    @elseif($res->status == "picked")
                                        <span class="text-success">Picked</span>                                        
                                    @elseif($res->status == "delivered")
                                        <span class="text-success">Delivered</span>                                        
                                    @elseif($res->status == "rejected")
                                        <span class="text-danger">Rejected</span>                                        
                                    @else
                                        <span class="text-primary">Pending</span>                                        
                                    @endif
                                </td>
                                
                                <td>

                                    <div class="d-flex px-0 mt-0 mb-0">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <!-- Show -->
                                            <a href="{{ route('frontend.components.reservation.show', $res)}}"
                                               class="btn btn-secondary btn-xs"><i class="fa fa-eye" title="Show"></i>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.components.reservation.edit', $res)}}"
                                               class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit"></i>
                                            </a>

                                            <!-- Delete -->
                                            <a href="{{ route('admin.components.reservation.delete', $res)}}"
                                               class="btn btn-danger btn-xs"><i class="fa fa-trash"
                                                                                title="Delete"></i>
                                            </a>
                                            
                                        </div>
                                    </div>
                                    
                                </td>

                            </tr>
                        @endforeach
                    </table>

                    {{ $reservation->links() }}
                </div>
            </x-slot>
        </x-backend.card>
    </div>
@endsection