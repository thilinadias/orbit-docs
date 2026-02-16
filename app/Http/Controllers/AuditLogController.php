<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        // Enforce Admin permission
        if (!Gate::allows('user.manage') && !auth()->user()->is_super_admin) {
             abort(403);
        }

        $query = $this->getFilteredQuery($request);

        $logs = $query->latest()->paginate(20)->withQueryString();

        // Get unique modules for filter dropdown
        $modules = ActivityLog::select('log_name')->distinct()->pluck('log_name');

        return view('audit_logs.index', compact('logs', 'modules'));
    }

    public function export(Request $request)
    {
        // Enforce Admin permission
        if (!Gate::allows('user.manage') && !auth()->user()->is_super_admin) {
             abort(403);
        }

        $fileName = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $query = $this->getFilteredQuery($request);

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'User', 'IP Address', 'Location', 'Module', 'Action', 'Subject', 'Description', 'Old Values', 'New Values'];

        $callback = function() use ($query, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $query->latest()->chunk(100, function($logs) use ($file) {
                foreach ($logs as $log) {
                    $row = [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'System / Deleted User',
                        $log->ip_address ?? 'N/A',
                        'N/A', // Location placeholder
                        ucfirst($log->log_name),
                        ucfirst($log->action),
                        $log->subject_type ? class_basename($log->subject_type) . ' #' . $log->subject_id : 'N/A',
                        $log->description,
                        $log->old_values ? json_encode($log->old_values) : '',
                        $log->new_values ? json_encode($log->new_values) : '',
                    ];

                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    private function getFilteredQuery(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject']);

        // Search/Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        return $query;
    }
}
