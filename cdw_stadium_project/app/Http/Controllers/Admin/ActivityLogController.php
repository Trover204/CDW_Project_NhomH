<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::with('user:id,name,email')->latest()->paginate(20);

        return view('admin.activity-logs.index', compact('activityLogs'));
    }
}
