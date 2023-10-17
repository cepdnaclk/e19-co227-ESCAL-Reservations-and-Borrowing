@extends('frontend.layouts.cart_view') 

@section('title', __('Products Available'))

@section('content')  


<div class="container">
    
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>UserId</th>
                    <th>Ordered_Date</th>
                    <th>Items</th>
                    <th>Picked_date</th>
                    <th>Due_date_to_return</th>
                    <th>Returned_date</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>

    

                <tr>
                   <td>{{$order->user_id}}</td>
                   <td>{{$order->ordered_date}}</td>
                   <td>
                        @foreach ($order->componentItems as $item)
                            <ul>
                                {{$item->title}}
                            </ul>
                        @endforeach
                   </td>
                   <td>{{$order->picked_date}}</td>
                   <td>{{$order->due_date_to_return}}</td>
                   <td>{{$order->returned_date}}</td>
                   <td>pending</td>
                  
                </tr>
              
            </tbody>

        </table>

    </div>

</div>

</div>

@endsection 