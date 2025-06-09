<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Patron;

class InquiryController extends Controller
{
    /**
     * Display a listing of the inquiries.
     */
    public function index()
    {
        // Fetch all inquiries with related patron info
        $inquiries = Inquiry::with('patron')->latest()->get();

        return view('admin.a_inquiry', compact('inquiries'));
    }

    /**
     * Show the specific inquiry details (optional).
     */
    public function show($id)
    {
        $inquiry = Inquiry::with('patron')->findOrFail($id);
        return view('admin.inquiry_show', compact('inquiry'));
    }

    /**
     * (Optional) Change inquiry status or convert to reservation.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $inquiry->status = $request->input('status');
        $inquiry->save();

        return redirect()->route('admin.inquiry')->with('success', 'Inquiry status updated.');
    }

    public function updateStatusAjax(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,In Progress,Completed,Cancelled',
        ]);

        $inquiry = Inquiry::find($id);
        if (!$inquiry) {
            return response()->json(['success' => false, 'message' => 'Inquiry not found.'], 404);
        }

        $inquiry->status = $request->input('status');
        $inquiry->save();

        return response()->json(['success' => true]);
    }

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

        $patron = Patron::firstOrCreate(
            ['email' => $request->email],
            [
                'name'           => $request->name,
                'contact_number' => $request->contact_number,
            ]
        );

        Inquiry::create([
            'patron_id'     => $patron->id,
            'date'          => $request->date,
            'time'          => $request->time,
            'venue'         => $request->venue,
            'event_type'    => $request->event_type,
            'theme_motif'   => $request->theme_motif,
            'message'       => $request->message,
            'status'        => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Inquiry successfully created!');
    }

    public function create()
    {
        return view('admin.a_reserve');
    }
}
