<?php

namespace App\Http\Controllers;

use App\Sale;
use App\SaleItem;
use App\Product;
use App\Receivable;
use App\Expense;
use App\Services\ProfitAnalysisService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $profitService;

    public function __construct(ProfitAnalysisService $profitService)
    {
        $this->profitService = $profitService;
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function sales()
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $sales = Sale::with(['customer', 'user', 'items'])
            ->where('status', 'completed')
            ->whereBetween('sold_at', [$startDate, $endDate])
            ->orderByDesc('sold_at')
            ->paginate(50);

        $summary = DB::table('sales')
            ->where('status', 'completed')
            ->whereBetween('sold_at', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as transaction_count, SUM(grand_total) as total_revenue, SUM(total_discount) as total_discount')
            ->first();

        return view('reports.sales', [
            'sales' => $sales,
            'summary' => $summary,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function inventory()
    {
        $products = Product::with(['stock', 'category', 'unit'])
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(50);

        return view('reports.inventory', ['products' => $products]);
    }

    public function profit()
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $summary = $this->profitService->getProfitSummary($startDate, $endDate);
        $byProduct = $this->profitService->getProfitByProduct($startDate, $endDate);
        $byCategory = $this->profitService->getProfitByCategory($startDate, $endDate);

        return view('reports.profit', [
            'summary' => $summary,
            'by_product' => $byProduct,
            'by_category' => $byCategory,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function receivable()
    {
        $receivables = Receivable::with(['customer', 'sale'])
            ->orderBy('due_date')
            ->paginate(50);

        $summary = DB::table('receivables')
            ->selectRaw('COUNT(*) as total_receivables, SUM(total_amount) as total_amount, SUM(remaining_amount) as total_remaining')
            ->first();

        return view('reports.receivable', [
            'receivables' => $receivables,
            'summary' => $summary,
        ]);
    }

    public function expense()
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $expenses = Expense::with(['category', 'user'])
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderByDesc('expense_date')
            ->paginate(50);

        $summary = DB::table('expenses')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as transaction_count, SUM(amount) as total_amount')
            ->first();

        $byCategory = DB::table('expenses')
            ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('expense_categories.name, SUM(expenses.amount) as total_amount')
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->get();

        return view('reports.expense', [
            'expenses' => $expenses,
            'summary' => $summary,
            'by_category' => $byCategory,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ]);
    }
}
