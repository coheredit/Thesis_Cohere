<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationData;
    public $reply;

    /**
     * Create a new message instance.
     */
    public function __construct($reservationData, $reply)
    {
        $this->reservationData = $reservationData;
        $this->reply = $reply;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        if ($this->reply) {
            return $this->subject('Villa Salud reply to your inquiry.')
                ->view('emails.reply') // You'll create this Blade view
                ->with([
                    'data' => $this->reply,
                ]);
        }

        return $this->subject('Your Reservation Has Been Submitted')
            ->view('emails.reservation_submitted') // You'll create this Blade view
            ->with([
                'data' => $this->reservationData,
            ]);
    }
}
