<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Inquiry;
use App\Models\Reservation;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return view('patron.p_payment');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'tracking_code' => 'required|string',
            'reference_code' => 'nullable|string|max:100',
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $inquiry = Inquiry::where('tracking_code', $request->tracking_code)
            ->whereHas('patron', fn($q) => $q->where('email', $request->email))
            ->first();

        if (!$inquiry) {
            return back()->with('error', 'Invalid email or tracking code.');
        }

        $reservation = Reservation::where('inquiry_id', $inquiry->id)->first();

        if (!$reservation) {
            return back()->with('error', 'No reservation found for this tracking code.');
        }

        $path = $request->file('receipt')->store('payments', 'public');

        Payment::create([
            'reserve_id' => $reservation->id,
            'reference_code' => $request->reference_code,
            'receipt_path' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Receipt uploaded successfully. Your payment is now under review.');
    }
}
