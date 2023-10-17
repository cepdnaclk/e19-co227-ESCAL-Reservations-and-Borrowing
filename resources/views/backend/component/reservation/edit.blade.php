@extends('backend.layouts.app')

@section('title', __('Reservation'))

@section('breadcrumb-links')
    @include('backend.reservation.includes.breadcrumb-links')
@endsection

@section('content')
    <div>
        {!! Form::open(['url' => route('admin.components.reservation.update',
                      compact('reservation')),
                      'method' => 'put',
                      'class' => 'container',
                      'files'=>true,
                      'enctype'=>'multipart/form-data'
          ]) !!}

        <x-backend.card>
            <x-slot name="header">
                Reservation : Edit
            </x-slot>

            <x-slot name="body">
                
                <!-- Picked Date -->
                <div class="form-group row">
                    {!! Form::label('picked_date', 'Picked Date', ['class' => 'col-md-2 col-form-label']) !!}
                    
                    <div class="col-md-3">
                        {!! Form::date('picked_date', $reservation->picked_date, ['class' => 'form-control']) !!}
                        
                        @error('picked_date')
                        <strong>{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
                
                <!-- Due Date -->
                <div class="form-group row">
                    {!! Form::label('due_date_to_return', 'Due Date', ['class' => 'col-md-2 col-form-label']) !!}
                    
                    <div class="col-md-3">
                        {!! Form::date('due_date_to_return', $reservation->due_date_to_return, ['class' => 'form-control']) !!}
                        
                        @error('due_date_to_return')
                        <strong>{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- Returned Date -->
                <div class="form-group row">
                    {!! Form::label('returned_date', 'Returned Date', ['class' => 'col-md-2 col-form-label']) !!}
                    
                    <div class="col-md-3">
                        {!! Form::date('returned_date', $reservation->returned_date, ['class' => 'form-control']) !!}
                        
                        @error('returned_date')
                        <strong>{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- Comments -->
                <div class="form-group row">
                    {!! Form::label('comments', 'Comments', ['class' => 'col-md-2 col-form-label']) !!}

                    <div class="col-md-10">
                        {!! Form::textarea('comments', $reservation->comments, ['class'=>'form-control', 'rows'=>3 ]) !!}
                        @error('comments')
                        <strong>{{ $message }}</strong>
                        @enderror
                    </div>
                </div>    

                <!-- Status -->
                <div class="form-group row">
                    {!! Form::label('status', 'Status*', ['class' => 'col-md-2 form-check-label']) !!}
                    <div class="col-md-10">
                        <select name="status" id="status" $reservation->status>
                            <option value="ready">Ready</option>
                            <option value="rejected" >Reject</option>
                            <option value="delivered" >Delivered</option>
                            <option value="picked" >Picked</option>
                        </select>
                        @error('status')
                        <strong>{{ $message }}</strong>
                        @enderror
                    </div>

                </div>
            </x-slot>

            <x-slot name="footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary float-right']) !!}
            </x-slot>


        </x-backend.card>
        </form>
    </div>
@endsection