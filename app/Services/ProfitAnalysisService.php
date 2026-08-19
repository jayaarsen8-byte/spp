<?php

namespace App\Services;

use App\Sale;
use App\SaleItem;
use App\Expense;
use Carbon\Carbon;

class ProfitAnalysisService
{
    public function getProfitSummary($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $sales = Sale::whereBetween('sold_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('items')
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');

        $totalRevenue = $sales->sum('grand_total');
        $totalCost = $sales->flatMap->items->sum('quantity') > 0 
            ? $sales->flatMap->items->sum(fn($item) => $item->quantity * $item->cost_price)
            : 0;
        $grossProfit = $totalRevenue - $totalCost;
        $netProfit = $grossProfit - $expenses;
        $totalDiscount = $sales->sum('total_discount');
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue * 100) : 0;

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_cost' => round($totalCost, 2),
            'gross_profit' => round($grossProfit, 2),
            'total_expenses' => round($expenses, 2),
            'net_profit' => round($netProfit, 2),
            'total_discount' => round($totalDiscount, 2),
            'profit_margin' => round($profitMargin, 2),
            'transaction_count' => $sales->count(),
        ];
    }

    public function getProfitByProduct($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('sold_at', [$startDate, $endDate])
                  ->where('status', 'completed');
        })
        ->selectRaw('product_id, SUM(profit) as total_profit, SUM(subtotal) as total_revenue, COUNT(*) as quantity_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_profit')
        ->get()
        ->map(function ($item) {
            $item->product = $item->product()->first();
            return $item;
        });
    }

    public function getProfitByCategory($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('sold_at', [$startDate, $endDate])
                  ->where('status', 'completed');
        })
        ->whereHas('product')
        ->with('product.category')
        ->selectRaw('product_id, SUM(profit) as total_profit, SUM(subtotal) as total_revenue')
        ->groupBy('product_id')
        ->get()
        ->groupBy(fn($item) => $item->product->category->name)
        ->map(function ($items, $category) {
            return [
                'category' => $category,
                'total_profit' => round($items->sum('total_profit'), 2),
                'total_revenue' => round($items->sum('total_revenue'), 2),
            ];
        })
        ->values();
    }
}
