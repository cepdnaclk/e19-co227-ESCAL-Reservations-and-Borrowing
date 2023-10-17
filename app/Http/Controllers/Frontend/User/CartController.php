<?php

namespace App\Http\Controllers\Frontend\User;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\ComponentItem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComponentReservationMail;

/**
 * Class CartController.
 */
class CartController
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index()
    {
        $componentItem = ComponentItem::all();
        return view('frontend.user.products', compact('componentItem'));
    }

    public function cart()
    {
        return view('frontend.user.cart');
    }

    public function addToCart($id)
    {
        $componentItem = ComponentItem::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $componentItem->title,
                "quantity" => 1,
                "code" => $componentItem->id,
                "image" => $componentItem->thumbURL()
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function placeOrder(Request $request)
    {
        $web = request()->validate([
            'product' => 'required|array|min:1', // TODO: Validate properly
            'quantity' => 'required|array|min:1'
        ]);

        $data['ordered_date'] = Carbon::now()->format('Y-m-d');
        $data['user_id'] = $request->user()->id;
        $order = new Order($data);
        $order->status = 'pending';
        $order->save();

        $reservation = $order;

        for ($i = 0; $i < count($web['product']); $i++) {
            $order->componentItems()->attach($web['product'][$i], array('quantity' => $request->quantity[$i]));
        }

        if (App::environment(['staging'])) {
            dd('Not sending emails');
        } 
        else {
        try {
            $enum = explode('@', $reservation->user_info()->email)[0];

            // get enumber
            $batch = substr($enum, 1, 2);
            $regnum = substr($enum, 3, 5);

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
                ->send(new ComponentReservationMail($reservation->user_info(), $reservation));


        } catch (\Exception $e) {
            return response()->json([
                'error' => 'enumber null',
                'code' => $e->getCode(), // Include the exception code
                'message' => $e->getMessage(), // Include the exception message
            ], 404);
        }
    }

    //    $user_id=$request->user()->id;
       // $order_date=$data['ordered_date'];
      $orders=Order::where('id',$request->user()->id)->get();
      $orders=$order;
   // return  Order::where('id',$request->user()->id)->get();;
    return view('frontend.orders.index', compact('order'));
     return response()->json($order,200);
    }    

}

