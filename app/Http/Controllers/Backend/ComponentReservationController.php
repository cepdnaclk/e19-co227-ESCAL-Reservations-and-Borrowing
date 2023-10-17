<?php

namespace App\Http\Controllers\Backend;

use DateTime;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class ComponentReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $reservation = Order::paginate(12);
        return view('backend.component.reservation.index', compact('reservation'));
    }

    public function show(Order $reservation)
    {
        return view('backend.component.reservation.show', compact('reservation'));
    }

    public function edit_main(Order $reservation)
    {
        return view('backend.component.reservation.edit', compact('reservation'));
    }

    public function update_main(Request $request, Order $reservation)
    {
        $data = request()->validate([
            'picked_date' => 'date|nullable',
            'due_date_to_return' => 'date|nullable',
            'returned_date' => 'date|nullable',
            'status' => 'string|nullable',
            'comments' => 'string|nullable'
        ]);

        $data = [
            'due_date_to_return' => isset($data['due_date_to_return']) ? (new DateTime($data['due_date_to_return']))->format('Y-m-d') : null,
            'picked_date' => isset($data['picked_date']) ? (new DateTime($data['picked_date']))->format('Y-m-d') : null,
            'returned_date' => isset($data['returned_date']) ? (new DateTime($data['returned_date']))->format('Y-m-d') : null,
            'status' => $request['status'],
            'comments' => $request['comments'],
        ];
        

        if($data['status'] != 'delete'){
            $reservation->update($data);

            // sending email stating that reservation has approved

            if (App::environment(['staging'])) {
                dd('Not sending emails');
            } 
            // else {
            // try {
            //     $enums = explode(',', $booking->E_numbers);

            //     foreach ($enums as $enum) {

            //         // get enumber
            //         $enum1 = explode('/', $enum);
            //         $batch = $enum1[1];
            //         $regnum = $enum1[2];

            //         //set api url
            //         $apiurl = 'https://api.ce.pdn.ac.lk/people/v1/students/E' . '' . $batch . '/' . $regnum . '/';

            //         //api call
            //         $response = Http::withoutVerifying()
            //             ->get($apiurl);

            //         //extract email address
            //         $email = ($response['emails']['faculty']['name'] . '@' . $response['emails']['faculty']['domain']);

            //         // $email = 'e19453@eng.pdn.ac.lk';

            //         //get user
            //         $user = auth()->user();

            //         //send mail
            //         Mail::to($email)
            //             ->send(new StationConfirmationMail(auth()->user(), $station, $booking));

            //     }
            // } catch (\Exception $e) {
            //     return response()->json([
            //         'error' => 'enumber null',
            //         'code' => $e->getCode(), // Include the exception code
            //         'message' => $e->getMessage(), // Include the exception message
            //     ], 404);
            // }
        // }



            return redirect()->route('admin.component.reservation.index')->with('Success', 'Reservation status was saved !'); 
        }
        else {
            $reservation->delete();
            return redirect()->route('admin.component.reservation.index')->with('Success', 'Reservation was deleted !');
        }
                       
    }

    public function approve(Request $request, Order $reservation)
    {
        $data = request()->validate([
            'comments' => 'string|nullable',
            'status' => 'string|required'
        ]);

        $data = [
            'status' => $request->status,
            
        ];
            return redirect()->route('admin.components.reservation.index')->with('Success', 'Reservation was deleted !');
    }

    public function delete(Order $reservation){
        $reservation->delete();
        return redirect()->back()->with('Success', 'Reservation was deleted !');
    }
}
