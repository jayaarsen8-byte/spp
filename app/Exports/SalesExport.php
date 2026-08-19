<?php

namespace App\Exports;

use App\Sale;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate)->endOfDay();
    }

    public function collection()
    {
        return Sale::with(['customer', 'user'])
            ->where('status', 'completed')
            ->whereBetween('sold_at', [$this->startDate, $this->endDate])
            ->orderByDesc('sold_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Date',
            'Customer',
            'Type',
            'Subtotal',
            'Discount',
            'Grand Total',
            'Payment',
            'Change',
            'Receivable',
            'Cashier',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->sold_at->format('d-m-Y H:i'),
            $sale->customer?->name ?? 'Walk-in',
            $sale->price_type,
            number_format($sale->subtotal_normal, 2),
            number_format($sale->total_discount, 2),
            number_format($sale->grand_total, 2),
            number_format($sale->payment_amount, 2),
            number_format($sale->change_amount, 2),
            number_format($sale->receivable_amount, 2),
            $sale->user->name,
        ];
    }
}
