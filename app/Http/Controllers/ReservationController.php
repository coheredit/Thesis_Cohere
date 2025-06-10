<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patron;
use App\Models\Inquiry;

class ReservationController extends Controller
{
    public function create(Request $request)
    {
        if ($request->is('admin/*')) {
            return view('admin.a_reserve');
        }
        return view('patron.p_mreserve');
    }

    
    public function store(Request $request)
    {
         // dd($request->all());


        $validated = $request->validate([
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

        if (empty($validated['name'])) {
            return redirect()->back()->withErrors(['name' => 'Name is required.']);
        }

        $patron = Patron::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name'           => $validated['name'],
                'contact_number' => $validated['contact_number'],
            ]
        );

        // dd($patron);

        Inquiry::create([
            'patron_id'         => $patron->patron_id,
            'event_type'        => $validated['event_type'],
            'theme_motif'       => $validated['theme_motif'],
            'venue'             => $validated['venue'],
            'other_venue'       => $request->other_venue,
            'other_event_type'  => $request->other_event_type,
            'other_theme_motif' => $request->other_theme_motif,
            'date'              => $validated['date'],
            'time'              => $validated['time'],
            'message'           => $validated['message'],
            'status'            => 'pending',
        ]);

        return redirect()->back()->with('success', 'Inquiry successfully created!');
    }
}
