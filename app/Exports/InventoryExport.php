<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with(['category', 'unit', 'stock'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Barcode',
            'Name',
            'Category',
            'Unit',
            'Cost Price',
            'Consumer Price',
            'Applicator Price',
            'Buyer Price',
            'Current Stock',
            'Minimum Stock',
            'Status',
        ];
    }

    public function map($product): array
    {
        $status = 'Normal';
        if ($product->isOutOfStock()) {
            $status = 'Out of Stock';
        } elseif ($product->isLowStock()) {
            $status = 'Low Stock';
        }

        return [
            $product->sku,
            $product->barcode,
            $product->name,
            $product->category->name,
            $product->unit->abbreviation,
            number_format($product->cost_price, 2),
            number_format($product->consumer_price, 2),
            number_format($product->applicator_price, 2),
            number_format($product->buyer_price, 2),
            $product->stock?->quantity ?? 0,
            $product->minimum_stock,
            $status,
        ];
    }
}
