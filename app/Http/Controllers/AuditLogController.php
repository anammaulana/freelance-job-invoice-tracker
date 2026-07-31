<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        $auditLogs = AuditLog::query()
            ->latest()
            ->paginate(20);

        return view('audit-logs.index', compact('auditLogs'));
    }

    public function show(AuditLog $auditLog): View
    {
        return view('audit-logs.show', compact('auditLog'));
    }
}
