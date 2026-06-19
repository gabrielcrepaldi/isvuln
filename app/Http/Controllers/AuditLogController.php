<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Read-only listing of audit log entries.
     *
     * Audit logs are append-only: this controller intentionally exposes
     * NO store/update/edit/destroy/create methods.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // Optional filters
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('auditable_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        // Distinct values for the filter dropdowns
        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $users = User::whereIn('id', AuditLog::query()->distinct()->pluck('user_id')->filter())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('audit.index', compact('logs', 'actions', 'users'));
    }
}
