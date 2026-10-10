<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Kunin ang logs kasama ang user details, pinakabago muna, 20 per page
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        
        return view('admin.activity-logs.index', compact('logs'));
    }
}