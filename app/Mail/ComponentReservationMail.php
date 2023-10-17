<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Domains\Auth\Models\User;
use App\Models\Order;
use Illuminate\Queue\SerializesModels;

class ComponentReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reserver;
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $reserver, Order $order)
    {

        $this->reserver=$reserver;
        $this->order=$order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        return $this->markdown('emails.components.reservationmade')
            ->subject('MakerSpace Component Reservation');

    }
}
