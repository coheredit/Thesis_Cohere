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
        $adminId = Auth::guard('admin')->id(); 
        $adminId = Auth::id(); 
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
            // case 'all': do nothing (fetch all)
        }

        $activities = $query->latest()->get();

        return response()->json($activities);
    }
}
