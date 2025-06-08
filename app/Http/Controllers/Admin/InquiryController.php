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
}
