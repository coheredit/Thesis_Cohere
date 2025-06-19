<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|min:2',
            'payment_type' => 'required|in:full,half',
            'payment_method' => 'required|in:cash,gcash,bank',
            'email' => 'required|email',
            'reservation_code' => 'required|string|max:255',
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $trackingCode = 'VS-' . substr(time(), -6) . '-' . rand(1000, 9999);

        // Store receipt
        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $payment = Payment::create([
            'full_name' => $validated['full_name'],
            'payment_type' => $validated['payment_type'],
            'payment_method' => $validated['payment_method'],
            'tracking_code' => $trackingCode,
            'reservation_code' => $validated['reservation_code'],
            'receipt_path' => $receiptPath,
            'email' => $validated['email'],
        ]);

        return response()->json([
            'success' => true,
            'tracking_code' => $trackingCode,
        ]);
    }
}
