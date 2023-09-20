<?php

namespace App\Http\Controllers\Frontend;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\Stations;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Domains\Auth\Models\User;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Mail\StationReservationMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CalendarController extends Controller
{
    public function index(Stations $station)
    {
        // Get the model of the user logged in
        $userLoggedin = auth()->user();
        $events = [];

        // Get all the reservations for that particular station
        $bookings = Reservation::where('station_id', $station->id)->where('start_date', '>', Carbon::now()->subDays(8))->where('start_date', '>', Carbon::now()->subDays(8))->get();

        foreach ($bookings as $booking) {
            if ($booking->user_id != $userLoggedin['id']) {
                $color = 'blue';
            } else {
                $color = '#3E9CC2';
            }

            $userVar = User::find($booking->user_id);
            $events[] = [
                'id' => $booking->id,
                'title' => 'Reservation made by ' . $userVar->email . '  for  ' . $booking->E_numbers,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'stationId' => $station->id,
                'auth' => $booking->user_id,
                'color' => $color,
            ];
        }   

        $today = date('Y-m-d H:i:s');
        return view('frontend.calendar.index', ['events' => $events, 'station' => $station, 'userLoggedin' => $userLoggedin, 'today' => $today]);
    }

    public function list(Stations $station)
    {
        $events = array();
        $bookings = Reservation::where('station_id', $station->id)->where('start_date', '>', Carbon::now()->subDays(8))->get();

        foreach ($bookings as $booking) {
            $userVar = User::find($booking->user_id);
            $events[] = [
                'id' => $booking->id,
                'title' => 'Reservation made by ' . $userVar->email . '  for ' . $booking->E_numbers,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'stationId' => $station->id,
                'auth' => $booking->user_id,
            ];
        }
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $station = Session::get('station');
        $userLoggedin = auth()->user();

        // Use this for title validation - TODO
        // $stringLength = Str::length($request['title']);

        $request->validate([
            'start_date' => 'required',
            'title1' => ['required',
                        'regex:/^E\/\d{2}\/\d{3}$/'],
            'title2' => ['required',
                        'regex:/^E\/\d{2}\/\d{3}$/'],
            'comments' => 'required'
        ]);

        $request['title'] = $request->title1 . ', ' . $request->title2;

        $date = $request->start_date;

        $start_date = new DateTime($request->start_date);
        $end_date = $start_date->add(new DateInterval('PT120M'));

        // check whether input date is valid

        if ($start_date->format('Y-m-d H:i:s') < date('Y-m-d H:i:s') || $start_date->format('H:i:s') < '08:00:00' || $end_date->format('H:i:s') > '17:00:00') {
            return redirect('/stations/' . $station->id . '/reservations')->with('error', 'Unsuccessful. Invalid date');
        }


        // check for previous reservations

        $reservations1 = Reservation::where('start_date', '<=', $start_date)
            ->where('end_date', '>=', $start_date)
            ->get();
        
        $reservations2 = Reservation::where('start_date', '<=', $end_date)
            ->where('end_date', '>=', $end_date)
            ->get();

        if ($reservations1->count() > 0 || $reservations2->count() > 0) {
            return redirect('/stations/' . $station->id . '/reservations')->with('error', 'Unsuccessful. That time is already reserved');
        }


        // See if the user has already made a reservation on that day for this station
        $bookings1 = Reservation::whereDate('start_date', Carbon::parse($start_date))->where('user_id', $userLoggedin['id'])->where('station_id', $station->id)->get();

        // If the user has not made a reservation before
        if ($bookings1->count() == 0) {
            $booking = Reservation::create([

                'user_id' => $userLoggedin['id'],
                'start_date' => $request->start_date,
                'end_date' => $end_date,
                'station_id' => $station->id,
                'E_numbers' => $request->title,
                'duration' => $request->duration,
                'comments' => $request->comments
            ]);

            $color = null;

            if ($booking->title == 'Try') {
                $color = '#33C0FF';
            }

            //***********mails****************

            // Nuwan: This part is working. So I added a 'if condition' to this in such a way that
            // it will be only executed in a web server.
            // Environment can be setup in the .env file

            if (App::environment(['staging'])) {
                dd('Not sending emails');
            } else {

                try {
                    $enums = explode(',', $request->title);

                    foreach ($enums as $enum) {

                        // get enumber
                        $enum1 = explode('/', $enum);
                        $batch = $enum1[1];
                        $regnum = $enum1[2];

                        //set api url
                        $apiurl = 'https://api.ce.pdn.ac.lk/people/v1/students/E' . '' . $batch . '/' . $regnum . '/';

                        //api call
                        $response = Http::withoutVerifying()
                            ->get($apiurl);

                        //extract email address
                        $email = ($response['emails']['faculty']['name'] . '@' . $response['emails']['faculty']['domain']);

                        // $email = 'e19453@eng.pdn.ac.lk';

                        //get user
                        $user = auth()->user();

                        //send mail
                        Mail::to($email)
                            ->send(new StationReservationMail(auth()->user(), $station, $booking));

                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'code' => $e->getCode(), // Include the exception code
                        'message' => $e->getMessage(), // Include the exception message
                    ], 404);
                }
            }


            //**********mails****************

            // return response()->json([
            //     'id' => $booking->id,
            //     'start' => $booking->start_date,
            //     'end' => $booking->end_date,
            //     'title' => $booking->title,
            //     'station_id' => $station->id,
            //     'color' => $color ? $color : '',
            // ]);

            return redirect('/stations/'. $station->id .'/reservations')->with('message', 'Reservation request successfull');

        } else {
            // Print message
            return redirect('/stations/'. $station->id .'/reservations')->with('error', 'Reservation already made');
        }
    }

    public function update(Request $request, $id)
    {

        $station = Session::get('station');
        $userLoggedin = auth()->user();


        $booking = Reservation::find($id);

        //See whether update is made on the same day
        $dateOriginal = (new DateTime($booking->start_date))->format('Y-m-d');
        $dateNew = (new DateTime($request['start_date']))->format('Y-m-d');

        $date1 = Carbon::createFromFormat('Y-m-d', $dateOriginal);
        $date2 = Carbon::createFromFormat('Y-m-d', $dateNew);

        $result = $date1->eq($date2);

        $date = $request->begin;

        // See if the user has already made a reservation on that day for this station
        $bookings1 = Reservation::whereDate('start_date', $date)->where('user_id', $userLoggedin['id'])->where('station_id', $station->id)->get();

        if (!$booking) {
            return response()->json([
                'error' => 'Unable to locate the event',
            ], 404);
        }

        $booking->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return response()->json('Event updated');

        // TODO: If the start end times changed, it will be better to send the users an email
        // saying the time is changed (remind Google Calender events !)

        if (($result && (count($bookings1) == 1)) || (!$result && (count($bookings1) == 0))) {
            $booking->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return response()->json('Event updated');
        } else {
            // Print message
            return response()->json([
                'error' => 'Unable to locate the event',
            ], 404);
        }
    }

    public function destroy($id)
    {
        $booking = Reservation::find($id);

        if (!$booking) {
            return response()->json([
                'error' => 'Unable to locate the event',
            ], 404);
        }

        $booking->delete();

        return $id;
    }
}