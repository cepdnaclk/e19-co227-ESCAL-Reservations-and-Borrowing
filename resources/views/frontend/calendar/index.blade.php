@extends('frontend.layouts.app')

@section('title', appName() . ' | ' . $station->stationName)

@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <style>
        .fc-event {
            font-size: 14px;
            border-radius: 1px !important;
        }
    </style>
@endpush

@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function refreshPage() {
            window.location.reload();
        }
    </script>

    <script>
        {{-- $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Only load the events from last week to the future. Otherwise this can be a huge list in someday
            var booking = @json($events);
            var todayDate = @json($today);

            $('#calendar').fullCalendar({

                defaultView: 'agendaWeek',
                header: {
                    left: 'prev, next today',
                    center: 'title',
                    right: 'month, agendaWeek, agendaDay',
                },
                events: booking,
                selectable: true,
                selectHelper: true,

                dayClick: function(date, jsEvent, view) {
                    if (view.name === 'month') {
                        $('#calendar').fullCalendar('gotoDate', date);
                        $('#calendar').fullCalendar('changeView', 'agendaDay');
                    }
                },

                eventRender: function eventRender(event, element, view) {
                    $("#calendar .fc-title").each(function(i) {
                        $(this).html($(this).text());
                    });
                },

                select: function(start, end, allDays, view) {

                    if ((view.name === 'agendaDay' || view.name === 'agendaWeek') && (!isAnOverlapEvent(
                            start, end))) {

                        $('#bookingModal').modal('toggle');
                        $('#saveBtn').click(function() {
                            var title = $('#title').val();
                            var start_date = $.fullCalendar.formatDate(start,
                                "YYYY-MM-DD HH:mm:ss");
                            var end_date = $.fullCalendar.formatDate(end,
                                "YYYY-MM-DD HH:mm:ss");
                            var loggedIn = @json($userLoggedin);
                            var user = loggedIn['email'];
                            var begin = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
                            
                            const dateBegin = new Date(start_date); 
                            const dateToday = new Date(todayDate);

                            dateToday.setHours( dateToday.getHours() + 5 );
                            dateToday.setMinutes( dateToday.getMinutes() + 30 );

                            var ms1 = dateBegin.getTime() - dateToday.getTime();
                            var d1 = moment.duration(ms1);
                            var mins = d1.asMinutes();

                            console.log("Event Select");
                            console.log(start, end);
                            console.log(start_date, end_date);

                            // count hours
                            const date1 = new Date(start_date);
                            const date2 = new Date(end_date);

                            var ms = date2.getTime() - date1.getTime();
                            var d = moment.duration(ms);
                            var m = d.asMinutes();

                            const time_limit = 300;

                            console.log(ms, d, m);

                            // TODO: Validate the E Numbers

                            //Send to the database
                            if (m < time_limit) { //limit maximum time
                                $.ajax({
                                    url: "{{ route('frontend.calendar.store') }}",
                                    type: "POST",
                                    dataType: 'json',
                                    data: {
                                        title,
                                        start_date,
                                        end_date,
                                        begin,
                                        m
                                    },
                                    success: function(response) {

                                        //fill the calendar when event is entered instantaneously
                                        $('#bookingModal').modal('hide')
                                        $('#calendar').fullCalendar('renderEvent', {
                                            'title': response.title,
                                            'start': response.start,
                                            'end': response.end,
                                            'color': response.color,
                                            'auth': response.auth,
                                        });
                                        swal("Done!", "Event Created!", "success");

                                        // TODO: This is a temporary fix. Find a better way to this
                                        refreshPage();
                                    },
                                    error: function(error) {
                                        if (error.responseJSON.errors) {
                                            $('#titleError').html(error.responseJSON
                                                .errors.title);
                                        } else {
                                            $('#bookingModal').modal('hide')
                                            swal("Denied!",
                                                "Can not make multiple reservations in a day!",
                                                "warning");
                                        }
                                        console.log(error);
                                    },
                                });
                            } else {
                                swal("Permission Denied!", "You can not exceed 4 hours!",
                                    "warning");
                            }
                        });
                    }
                },
                editable: true,
                eventOverlap: false, //events cant overlap
                

                eventResize: function(event) {
                    var id = event.id;
                    var loggedIn = @json($userLoggedin);
                    var user = loggedIn['id'];

                    var start_date = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
                    var end_date = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD HH:mm:ss");

                    var ms = moment(end_date, "YYYY-MM-DD HH:MM:SS").diff(moment(start_date,
                        "YYYY-MM-DD HH:MM:SS"));

                    var d = moment.duration(ms);
                    var m = d.asMinutes();

                    var begin = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
                    const time_limit = 240;

                    if (event.auth === user) {
                        if (m <= time_limit) { //limit maximum time
                            $.ajax({
                                url: "{{ route('frontend.calendar.update', '') }}" + '/' + id,
                                type: "PATCH",
                                dataType: 'json',
                                data: {
                                    start_date,
                                    end_date,
                                    begin,
                                },
                                success: function(response) {

                                    $('#calendar').fullCalendar('refetchEvents', response);
                                    swal("Done!", "Event Updated!", "success");
                                },

                                error: function(error) {
                                    // if(error.responseJSON.errors) {
                                    //     $('#titleError').html(error.responseJSON.errors.title);
                                    // }
                                    console.log(error)
                                },
                            });
                        } else {
                            // Reset the time duration back to the previous value
                            revertFunc();
                            swal("Permission Denied!", "You can not exceed 4 hours!", "warning");
                        }
                    } else {
                        // Reset the time duration back to the previous value
                        revertFunc();
                        swal("Permission Denied!", "You can not update this event!", "warning");

                        //  This is a temporary fix. Find a better way to this
                        refreshPage();
                    }
                },

                //editable: true,

                eventDrop: function(event) {
                    var id = event.id;

                    // TODO: Update this without moment
                    var start_date = moment(event.start).format('YYYY-MM-DD HH:MM:SS');
                    var end_date = moment(event.end).format('YYYY-MM-DD HH:MM:SS');
                    var ms = moment(end_date, "YYYY-MM-DD HH:MM:SS").diff(moment(start_date,
                        "YYYY-MM-DD HH:MM:SS"));

                    var d = moment.duration(ms);
                    var m = d.asMinutes(); // Duration of the reservation in minutes

                    var begin = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
                    var loggedIn = @json($userLoggedin);
                    var user = loggedIn['id'];

                    const time_limit = 240;

                    const dateBegin = new Date(start_date);
                    const dateToday = new Date(todayDate);


                                url: "{{ route('frontend.calendar.update', '') }}" + '/' + id,
                                type: "PATCH",
                                dataType: 'json',
                                data: {
                                    start_date,
                                    end_date
                                },
                                success: function(response) {
                                    console.log(dateBegin, dateToday);
                                },
                                error: function(error) {
                                    // if(error.responseJSON.errors) {
                                    //     $('#titleError').html(error.responseJSON.errors.title);
                                    // }
                                    console.log(error)
                                },
                            });
                        } else {
                            // Reset the time duration back to the previous value
                            revertFunc(); 
                            swal("Permission Denied!", "You can not update this event!", "warning");
                        }
                    }else{
                        $('#bookingModal').modal('hide');
                        swal("Permission Denied!", "You can not make a reservation for a date that has passed", "warning");
                        // Reset the time duration back to the previous value
                        revertFunc(); 

                    }

                },
                eventClick: function(event) {
                    var id = event.id;
                    var loggedIn = @json($userLoggedin);
                    var user = loggedIn['id'];

                    if (event.auth === user) {
                        // TODO: It may ne nice if you can use a swal() like popup menu to get the confirmation

                        if (confirm('Are you sure you want to delete this event?')) {
                            $.ajax({
                                url: "{{ route('frontend.calendar.destroy', '') }}" + '/' + id,
                                type: "DELETE",
                                dataType: 'json',
                                success: function(response) {
                                    $('#calendar').fullCalendar('removeEvents', response);
                                    swal("Done!", "Event Deleted!", "success");
                                    refreshPage();

                                },
                                error: function(error) {
                                    // if(error.responseJSON.errors) {
                                    //     $('#titleError').html(error.responseJSON.errors.title);
                                    // }
                                    console.log(error)
                                },
                            });
                        }
                    } else {
                        swal("Permission Denied!", "You can not delete this event!", "warning");
                    }


                },
                //Not allowing to choose multiple events
                selectAllow: function(event) {
                    return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1,
                        'second').utcOffset(false), 'day');
                }
            });

            $("#bookingModal").on("hidden.bs.modal", function() {
                $('#saveBtn').unbind();
            });

        });

        function isAnOverlapEvent(eventStartDay, eventEndDay) {
            var events = $('#calendar').fullCalendar('clientEvents');

            for (let i = 0; i < events.length; i++) {
                const eventA = events[i];

                // start-time in between any of the events
                if (moment(eventStartDay).isAfter(eventA.start) && moment(eventStartDay).isBefore(eventA.end)) {
                    swal("Time Unavailable!", "Please choose another slot", "error");
                    return true;
                }
                //end-time in between any of the events
                if (moment(eventEndDay).isAfter(eventA.start) && moment(eventEndDay).isBefore(eventA.end)) {
                    swal("Time Unavailable!", "Please choose another slot", "error");
                    return true;
                }
                //any of the events in between/on the start-time and end-time
                if (moment(eventStartDay).isSameOrBefore(eventA.start) && moment(eventEndDay).isSameOrAfter(eventA.end)) {
                    swal("Time Unavailable!", "Please choose another slot", "error");
                    return true;
                }

            }
            return false;
        } --}}

    </script>
@endpush

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter your/your group members' E-numbers (comma
                        separated)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="title" placeholder="E/XX/XXX, E/XX/XXX, ...">
                    <span id="titleError" class="text-danger"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveBtn" class="btn btn-primary">Save event</button>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
            </svg>
            <strong class="p-2">Success!</strong> {{session('message')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16" role="img" aria-label="Success:">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
            </svg>
            <strong class="p-2">Error!</strong> {{session('error')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mt-5"><b>Schedule Reservation - {{ $station->stationName }} </b><br></h3>
                

                {{-- old calender to create reservations ( If possible implement this in future) --}}

                {{-- <h6 class="text-center">*Click and drag time period as required.<br>Click reservation to delete.
                    <br>Edit reservation by click and drag.
                </h6>

                <div class="col-md-11 offset-1 mb-3">
                    <fullcalendar-component :station-id="{{ $station->id }}" :user-id="{{ Auth()->user()->id }}" />
                </div> --}}

                

            </div>
        </div>
    </div>

    {{-- new form for component reservation --}}

    <section id="contact">
        <div class="container-lg">
          
          <div class="text-center">
            <p class="lead">Fill the following form</p>
          </div>

          <div class="row justify-content-center my-5">
            <div class="col-lg-6">
              
              <form method="POST" action="/stations/{{$station->id}}/reservations/create">
                @csrf
                <label for="enumber" class="form-label">E Numbers:</label>

                {{-- convert email to e number --}}
                @php
                    $useremail = $logged_in_user->email;

                    function convertEmailToENumber($email){
                        // Use a regular expression to extract the batch and registration numbers
                        if (preg_match('/^e(\d{2})(\d{3})@eng.pdn.ac.lk$/', strtolower($email), $matches)) {
                            $batch = $matches[1];
                            $regnum = $matches[2];
                            return "E/$batch/$regnum";
                        }
                        return null; // Invalid email format
                    }

                    $enum = convertEmailToENumber($useremail);
                @endphp

                <div class="input-group mb-4">
                  <input type="text" id="enumber" name="title1" class="form-control" placeholder="{{ $enum }}" value="{{ $enum }}" readonly/>
                </div>

                <div class="input-group mb-4">
                <input type="text" id="enumber" name="title2" class="form-control" placeholder="E/XX/XXX" />
                </div>

                <div class="input-group mb-4">
                <input type="text" id="enumber" name="title3" class="form-control" placeholder="E/XX/XXX" />
                </div>

                <div class="row mb-4">
                    <label for="inputdate" class="col-sm-4 col-form-label">Select Date & Time:</label>
                    <div class="col-sm-8">
                      <input type="datetime-local" class="form-control" id="inputdate" name="start_date">
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="inputduration" class="col-sm-4 col-form-label">Select Duration:</label>
                    <div class="col-sm-8">
                      <select class="form-select" aria-label="Default select example" id="inputduration" name="duration">
                        <option selected value="120">2 Hours</option>
                        <option value="180">3 Hours</option>
                        <option value="240">4 Hours</option>
                        <option value="300">5 Hours</option>
                      </select>
                    </div>
                </div>

                <div class="mb-4 mt-5 form-floating">
                  <textarea class="form-control" id="query" style="height: 140px" placeholder="query" name="comments"></textarea>
                  <label for="query">Your Comment...</label>
                </div>
                <div class="mb-4 text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>

      

@endsection

