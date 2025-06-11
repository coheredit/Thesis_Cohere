<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    public function getAvailability(): JsonResponse
    {
        $rows = DB::table('availabilities')->get();

        $statusMap = $rows->mapWithKeys(function ($row) {
            return [$row->date => $row->status]; // status is a string like 'Available', 'Full', etc.
        });

        return response()->json($statusMap);
    }
}
