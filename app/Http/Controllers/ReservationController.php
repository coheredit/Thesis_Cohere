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

        $patron = Patron::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name'           => $validated['name'],
                'contact_number' => $validated['contact_number'],
            ]
        );

        $eventType     = $validated['event_type'];
        $themeMotif    = $validated['theme_motif'];
        $venue         = $validated['venue'];

        $isEventOther  = strtolower(trim($eventType)) === 'others';
        $isThemeOther  = strtolower(trim($themeMotif)) === 'others';
        $isVenueOther  = strtolower(trim($venue)) === 'others';

        Inquiry::create([
            'patron_id'         => $patron->patron_id,
            'event_type'        => $isEventOther ? 'Others' : $eventType,    // Fixed: 'Others' not 'Other'
            'theme_motif'       => $isThemeOther ? 'Others' : $themeMotif,   // Fixed: 'Others' not 'Other'
            'venue'             => $isVenueOther ? 'Others' : $venue,        // Fixed: 'Others' not 'Other'
            'other_event_type'  => $isEventOther ? $request->input('other_event_type') : null,
            'other_theme_motif' => $isThemeOther ? $request->input('other_theme_motif') : null,
            'other_venue'       => $isVenueOther ? $request->input('other_venue') : null,
            'date'              => $validated['date'],
            'time'              => $validated['time'],
            'message'           => $validated['message'],
            'status'            => 'Pending',  // Also fixed capitalization to match enum
        ]);

        return redirect()->back()->with('success', 'Inquiry successfully created!');
    }
}
