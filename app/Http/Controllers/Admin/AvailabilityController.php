<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Availability;

class AvailabilityController extends Controller
{

    public function index()
    {
        try {
            $records = \App\Models\Availability::all()->mapWithKeys(function ($item) {
                return [$item->date->format('Y-n-j') => $item->status];
            });

            return response()->json($records);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // Toggle availability for a specific date (via AJAX)
    public function toggle(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:free,full,closed',
        ]);

        $availability = \App\Models\Availability::updateOrCreate(
            ['date' => $request->date],
            ['status' => $request->status]
        );

        return response()->json([
            'success' => true,
            'date' => $availability->date,
            'status' => $availability->status,
        ]);
    }
}
