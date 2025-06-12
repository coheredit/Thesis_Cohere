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
            $adminId = Auth::guard('admin')->id();
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

    public function store(Request $request)
    {
        $adminId = Auth::guard('admin')->id();

        if (!$adminId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $validated = $request->validate([
            'activity_type' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        ActivityLog::create([
            'admin_id' => $adminId,
            'activity_type' => $validated['activity_type'],
            'description' => $validated['description'] ?? '',
        ]);

        return response()->json(['success' => true, 'message' => 'Activity logged']);
    }

    public function allActivities()
    {
        try {
            $activities = ActivityLog::with('admin')
                ->latest()
                ->take(50)
                ->get()
                ->map(function ($log) {
                    return [
                        'description' => $log->description,
                        'type' => $log->activity_type,
                        'timestamp' => $log->created_at->toIso8601String(),
                        'admin_name' => optional($log->admin)->name ?? 'Unknown',
                    ];
                });

            return response()->json(['activities' => $activities]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch activity log.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
