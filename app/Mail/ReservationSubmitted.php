<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationData;

    /**
     * Create a new message instance.
     */
    public function __construct($reservationData)
    {
        $this->reservationData = $reservationData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Reservation Has Been Submitted')
            ->view('emails.reservation_submitted') // You'll create this Blade view
            ->with([
                'data' => $this->reservationData,
            ]);
    }
}
