<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Exports\InventoryExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function sales(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now();

        return Excel::download(
            new SalesExport($startDate, $endDate),
            'sales-report-' . date('YmdHis') . '.xlsx'
        );
    }

    public function inventory()
    {
        return Excel::download(
            new InventoryExport(),
            'inventory-report-' . date('YmdHis') . '.xlsx'
        );
    }

    public function profit(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now();

        return Excel::download(
            new SalesExport($startDate, $endDate),
            'profit-report-' . date('YmdHis') . '.xlsx'
        );
    }

    public function receivable()
    {
        return Excel::download(
            new SalesExport(Carbon::now()->startOfMonth(), Carbon::now()),
            'receivable-report-' . date('YmdHis') . '.xlsx'
        );
    }

    public function expense(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now();

        return Excel::download(
            new SalesExport($startDate, $endDate),
            'expense-report-' . date('YmdHis') . '.xlsx'
        );
    }
}
