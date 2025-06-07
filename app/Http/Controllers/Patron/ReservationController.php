<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patron;
use App\Models\Inquiry;

class ReservationController extends Controller
{
    public function create()
    {
        return view('patron.p_mreserve');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'event_type'     => 'required|string',
            'theme_motif'    => 'required|string',
            'venue'          => 'required|string',
            'date'           => 'required|date',
            'time'           => 'required',
            'message'        => 'required|string',
        ]);

        $patron = Patron::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'contact_number' => $request->contact_number,
        ]);

        Inquiry::create([
            'patron_id'         => $patron->id,
            'event_type'        => $request->event_type,
            'theme_motif'       => $request->theme_motif,
            'venue'             => $request->venue,
            'other_venue'       => $request->other_venue,
            'other_event_type'  => $request->other_event_type,
            'other_theme_motif' => $request->other_theme_motif,
            'date'              => $request->date,
            'time'              => $request->time,
            'message'           => $request->message,
            'status'            => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your reservation request has been submitted!');
    }
}
