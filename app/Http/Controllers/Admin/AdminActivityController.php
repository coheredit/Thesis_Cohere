<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdminActivityController extends Controller
{
    public function index(Request $request)
    {
        try {
            $adminId = Auth::guard('admin')->id();  // ✅ use only this
            if (!$adminId) {
                return response()->json(['error' => 'Admin not authenticated'], 401);
            }

            $filter = $request->input('filter', 'all');
            $query = ActivityLog::where('admin_id', $adminId);

            switch ($filter) {
                case 'login':
                    $query->whereIn('activity_type', ['Login', 'Logout']);
                    break;
                case 'profile':
                    $query->where('activity_type', 'Profile Change');
                    break;
                case 'system':
                    $query->where('activity_type', 'System Action');
                    break;
                    // 'all' does not apply a filter
            }

            $activities = $query->latest()->get();

            return response()->json($activities);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
