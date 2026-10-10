<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Kunin lang ang logs kung saan ang user_id ay katumbas ng naka-login na user
        $logs = ActivityLog::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(15);
        
        return view('customer.activity-logs.index', compact('logs'));
    }
}