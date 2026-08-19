<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Product;
use App\Customer;
use App\Receivable;
use App\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfDay = $now->copy()->startOfDay();

        if ($user->isOwner()) {
            return $this->ownerDashboard($startOfDay, $startOfMonth);
        }

        return $this->adminDashboard($startOfDay);
    }

    private function ownerDashboard($startOfDay, $startOfMonth)
    {
        $revenueToday = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfDay, $startOfDay->copy()->endOfDay()])
            ->sum('grand_total');

        $revenueMonth = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfMonth, $startOfMonth->copy()->endOfMonth()])
            ->sum('grand_total');

        $salesToday = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfDay, $startOfDay->copy()->endOfDay()])
            ->count();

        $lowStockProducts = Product::with('stock')
            ->whereRaw('stocks.quantity <= products.minimum_stock', [], 'left join stocks on products.id = stocks.product_id')
            ->where('is_active', true)
            ->count();

        $receivables = Receivable::whereIn('status', ['unpaid', 'partial', 'overdue'])->sum('remaining_amount');

        $expensesToday = Expense::whereBetween('expense_date', [$startOfDay, $startOfDay->copy()->endOfDay()])
            ->sum('amount');

        $chartData = $this->getSalesChartData($startOfMonth);
        $topProducts = $this->getTopProducts($startOfMonth);
        $insights = $this->getBusinessInsights($startOfMonth);

        return view('dashboard.owner', [
            'revenue_today' => round($revenueToday, 2),
            'revenue_month' => round($revenueMonth, 2),
            'sales_today' => $salesToday,
            'low_stock_count' => $lowStockProducts,
            'receivables' => round($receivables, 2),
            'expenses_today' => round($expensesToday, 2),
            'chart_data' => $chartData,
            'top_products' => $topProducts,
            'insights' => $insights,
        ]);
    }

    private function adminDashboard($startOfDay)
    {
        $salesToday = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfDay, $startOfDay->copy()->endOfDay()])
            ->count();

        $revenueToday = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfDay, $startOfDay->copy()->endOfDay()])
            ->sum('grand_total');

        $productsCount = Product::where('is_active', true)->count();
        $customersCount = Customer::where('is_active', true)->count();

        return view('dashboard.admin', [
            'sales_today' => $salesToday,
            'revenue_today' => round($revenueToday, 2),
            'products_count' => $productsCount,
            'customers_count' => $customersCount,
        ]);
    }

    private function getSalesChartData($startOfMonth)
    {
        $days = 31;
        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startOfMonth->copy()->addDays($i);
            if ($date->month !== $startOfMonth->month) break;

            $labels[] = $date->format('d');
            $data[] = Sale::where('status', 'completed')
                ->whereBetween('sold_at', [$date->startOfDay(), $date->endOfDay()])
                ->sum('grand_total');
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getTopProducts($startOfMonth)
    {
        return DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->whereBetween('sales.sold_at', [$startOfMonth, $startOfMonth->copy()->endOfMonth()])
            ->selectRaw('products.name, SUM(sale_items.subtotal) as total_sales, SUM(sale_items.profit) as total_profit')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_profit')
            ->limit(5)
            ->get();
    }

    private function getBusinessInsights($startOfMonth)
    {
        $insights = [];

        $topProduct = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->whereBetween('sales.sold_at', [$startOfMonth, $startOfMonth->copy()->endOfMonth()])
            ->selectRaw('products.name, SUM(sale_items.profit) as total_profit')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_profit')
            ->first();

        if ($topProduct) {
            $insights[] = "{$topProduct->name} menjadi produk dengan kontribusi profit terbesar bulan ini.";
        }

        $prevMonth = $startOfMonth->copy()->subMonth();
        $revenueThis = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfMonth, $startOfMonth->copy()->endOfMonth()])
            ->sum('grand_total');
        $revenuePrev = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$prevMonth, $prevMonth->copy()->endOfMonth()])
            ->sum('grand_total');

        if ($revenuePrev > 0) {
            $growth = (($revenueThis - $revenuePrev) / $revenuePrev * 100);
            $insights[] = "Penjualan " . ($growth > 0 ? "meningkat {$growth}%" : "menurun {$growth}%") . " dibanding bulan sebelumnya.";
        }

        $totalDiscount = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfMonth, $startOfMonth->copy()->endOfMonth()])
            ->sum('total_discount');
        if ($totalDiscount > 0) {
            $insights[] = "Diskon nego bulan ini mencapai Rp" . number_format($totalDiscount, 0);
        }

        $lowStockCount = Product::whereHas('stock', function ($q) {
            $q->whereRaw('quantity <= products.minimum_stock');
        })->count();
        if ($lowStockCount > 0) {
            $insights[] = "{$lowStockCount} produk berada di bawah minimum stock.";
        }

        return $insights;
    }
}
