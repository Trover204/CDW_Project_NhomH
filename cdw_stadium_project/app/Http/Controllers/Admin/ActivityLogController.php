<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $actionFilter = trim((string) $request->input('action', ''));
        $moduleFilter = trim((string) $request->input('module', ''));
        $search = trim((string) $request->input('q', ''));

        $query = ActivityLog::with('user:id,name,email')
            ->when($actionFilter !== '', fn ($q) => $q->where('action', $actionFilter))
            ->when($moduleFilter !== '', fn ($q) => $q->where('description', 'like', "% {$moduleFilter} ID:%"))
            ->when($search !== '', function ($q) use ($search) {
                $like = "%{$search}%";
                $q->where(function ($sub) use ($like) {
                    $sub->where('description', 'like', $like)
                        ->orWhere('ip_address', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like));
                });
            });

        $activityLogs = $query->latest()->paginate(20)->withQueryString();
        $modules = ActivityLog::MODEL_LABELS;
        $actions = ActivityLog::ACTION_LABELS;

        return view('admin.activity-logs.index', compact(
            'activityLogs',
            'modules',
            'actions',
            'actionFilter',
            'moduleFilter',
            'search'
        ));
    }
}


