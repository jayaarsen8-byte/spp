<?php

namespace App\Http\Controllers;

use App\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function index()
    {
        $logs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(100);

        return view('audit-logs.index', ['logs' => $logs]);
    }
}
