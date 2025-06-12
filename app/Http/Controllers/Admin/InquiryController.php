<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Patron;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('patron')->latest()->get();
        return view('admin.a_inquiry', compact('inquiries'));
    }

    public function show($id)
    {
        $inquiry = Inquiry::with('patron')->findOrFail($id);
        return view('admin.inquiry_show', compact('inquiry'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $inquiry = Inquiry::with('patron')->find($id);
        $inquiry->status = $request->input('status');
        $inquiry->save();

        return redirect()->route('admin.inquiry')->with('success', 'Inquiry status updated.');
    }

    public function updateStatusAjax(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,In Progress,Completed,Cancelled',
        ]);

        $inquiry = Inquiry::with('patron')->find($id);
        if (!$inquiry) {
            return response()->json(['success' => false, 'message' => 'Inquiry not found.'], 404);
        }

        $newStatus = $request->input('status');
        $inquiry->status = $newStatus;
        $inquiry->save();

        // ✅ If marked as Completed, convert to reservation
        if ($newStatus === 'Completed') {
            $existing = \App\Models\Reservation::where('inquiry_id', $inquiry->inquiry_id)->first();

            if (!$existing) {
                // 🔍 Log the inquiry data for inspection
                Log::info('Attempting to create reservation from inquiry:', $inquiry->toArray());

                try {
                    \App\Models\Reservation::create([
                        'inquiry_id'         => $inquiry->inquiry_id,
                        'patron_id'          => $inquiry->patron_id,
                        'date'               => $inquiry->date,
                        'time'               => $inquiry->time,
                        'venue'              => $inquiry->venue,
                        'event_type'         => $inquiry->event_type,
                        'theme_motif'        => $inquiry->theme_motif,
                        'message'            => $inquiry->message,
                        'status'             => 'Active'
                    ]);
                } catch (\Exception $e) {
                    // ❌ Log the exception with full message
                    Log::error('Failed to create reservation: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Reservation creation failed: ' . $e->getMessage()
                    ], 500);
                }
            }
        }


        return response()->json(['success' => true]);
    }


    public function store(Request $request)
    {
        // Validate all fields
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'contact_number' => 'required|string|max:30',
            'date'           => 'required|date',
            'time'           => 'required|string',
            'venue'          => 'required|string|max:255',
            'event_type'     => 'required|string|max:255',
            'theme_motif'    => 'nullable|string|max:255',
            'message'        => 'nullable|string',
        ]);

        try {
            // Create or update the patron
            $patron = Patron::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'name'           => $validated['name'],
                    'contact_number' => $validated['contact_number'],
                ]
            );

            if (!$patron || !$patron->patron_id) {
                return redirect()->back()->with('error', 'Failed to create or find patron.');
            }

            // Handle "Others" values
            $isEventOther = strtolower(trim($validated['event_type'])) === 'others';
            $isThemeOther = strtolower(trim($validated['theme_motif'])) === 'others';
            $isVenueOther = strtolower(trim($validated['venue'])) === 'others';

            // Create the inquiry
            Inquiry::create([
                'patron_id'         => $patron->patron_id, // make sure this is correct
                'admin_id'          => auth('admin')->id(),
                'created_by_type'   => 'admin',
                'date'              => $validated['date'],
                'time'              => $validated['time'],
                'venue'             => $isVenueOther ? 'Others' : $validated['venue'],
                'event_type'        => $isEventOther ? 'Others' : $validated['event_type'],
                'theme_motif'       => $isThemeOther ? 'Others' : $validated['theme_motif'],
                'other_event_type'  => $isEventOther ? $request->input('other_event_type') : null,
                'other_theme_motif' => $isThemeOther ? $request->input('other_theme_motif') : null,
                'other_venue'       => $isVenueOther ? $request->input('other_venue') : null,
                'message'           => $validated['message'],
                'status'            => 'Pending',
            ]);

            return redirect()->back()->with('success', 'Inquiry successfully created!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to store inquiry. Error: ' . $e->getMessage());
        }
    }
}
