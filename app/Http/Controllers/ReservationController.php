<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patron;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSubmitted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        Log::info('may data', $request->all());
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

        try {
            $inquiry = Inquiry::create([
                'patron_id'         => $patron->patron_id,
                'event_type'        => $isEventOther ? 'Others' : $eventType,    // Fixed: 'Others' not 'Other'
                'other_event_type'  => $isEventOther ? $request->input('other_event_type') : null,
                'theme_motif'       => $isThemeOther ? 'Others' : $themeMotif,   // Fixed: 'Others' not 'Other'
                'other_theme_motif' => $isThemeOther ? $request->input('other_theme_motif') : null,
                'venue'             => $isVenueOther ? 'Others' : $venue,        // Fixed: 'Others' not 'Other'
                'other_venue'       => $isVenueOther ? $request->input('other_venue') : null,
                'date'              => $validated['date'],
                'time'              => $validated['time'],
                'message'           => $validated['message'],
                'status'            => 'Pending',  // Also fixed capitalization to match enum
            ]);

            Mail::to($validated['email'])->send(new ReservationSubmitted([
                'name'        => $validated['name'],
                'date'        => $validated['date'],
                'time'        => $validated['time'],
                'venue'       => $venue,
                'event_type'  => $eventType,
                'theme_motif' => $themeMotif,
                'message' => $validated['message'],
                'tracking_code' => $inquiry['tracking_code'],
            ]));
        } catch (\Throwable $th) {
            Log::error('Failed to insert inquiry: ' . $th->getMessage());
        }


        return redirect()->back()->with('success', 'Inquiry successfully created!');
    }

    public function sendReply(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        try {
            Mail::raw($request->message, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reply to Your Inquiry');
            });

            return response()->json(['success' => true, 'message' => 'Reply sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send reply: ' . $e->getMessage()]);
        }
    }
}
