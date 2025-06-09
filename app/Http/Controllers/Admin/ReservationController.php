<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function create()
    {
        return view('admin.reserve');
    }

    /**
     * Store the reservation submitted by the admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'date'           => 'required|date',
            'time'           => 'required|string',
            'venue'          => 'required|string|max:255',
            'event_type'     => 'required|string|max:255',
            'theme_motif'    => 'nullable|string|max:255',
            'message'        => 'nullable|string',
        ]);

        Reservation::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'contact_number' => $request->contact_number,
            'date'           => $request->date,
            'time'           => $request->time,
            'venue'          => $request->venue,
            'event_type'     => $request->event_type,
            'theme_motif'    => $request->theme_motif,
            'message'        => $request->message,
        ]);

        return redirect()->back()->with('success', 'Reservation successfully created!');
    }
}
