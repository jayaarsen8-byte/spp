<?php

namespace App\Services;

use App\Product;
use App\SaleItem;
use Decimal\Decimal;

class SaleCalculationService
{
    public function calculateSaleItem($product, $quantity, $length = null, $sellingPrice = null)
    {
        $quantity = new Decimal($quantity);
        $masterPrice = new Decimal($product->getPrice($product->price_type ?? 'consumer'));
        $sellingPrice = new Decimal($sellingPrice ?? $masterPrice);
        $costPrice = new Decimal($product->cost_price);

        $result = [
            'normal_unit_price' => (float)$masterPrice,
            'selling_unit_price' => (float)$sellingPrice,
            'discount_per_unit' => (float)($masterPrice->subtract($sellingPrice)),
            'cost_price' => (float)$costPrice,
        ];

        if ($product->calculation_type === 'meter') {
            $totalMeter = $quantity;
            $subtotalNormal = $masterPrice->multiply($totalMeter);
            $subtotal = $sellingPrice->multiply($totalMeter);
            $totalDiscount = $subtotalNormal->subtract($subtotal);
            $profit = $subtotal->subtract($costPrice->multiply($totalMeter));

            $result['quantity'] = (float)$quantity;
            $result['length'] = (float)$quantity;
            $result['total_meter'] = (float)$totalMeter;
            $result['subtotal_normal'] = (float)$subtotalNormal;
            $result['total_discount'] = (float)$totalDiscount;
            $result['subtotal'] = (float)$subtotal;
            $result['profit'] = (float)$profit;
        } elseif ($product->calculation_type === 'sheet_meter') {
            $length = new Decimal($length ?? 0);
            $totalMeter = $quantity->multiply($length);
            $subtotalNormal = $masterPrice->multiply($totalMeter);
            $subtotal = $sellingPrice->multiply($totalMeter);
            $totalDiscount = $subtotalNormal->subtract($subtotal);
            $profit = $subtotal->subtract($costPrice->multiply($totalMeter));

            $result['quantity'] = (float)$quantity;
            $result['length'] = (float)$length;
            $result['total_meter'] = (float)$totalMeter;
            $result['subtotal_normal'] = (float)$subtotalNormal;
            $result['total_discount'] = (float)$totalDiscount;
            $result['subtotal'] = (float)$subtotal;
            $result['profit'] = (float)$profit;
        } else {
            $subtotalNormal = $masterPrice->multiply($quantity);
            $subtotal = $sellingPrice->multiply($quantity);
            $totalDiscount = $subtotalNormal->subtract($subtotal);
            $profit = $subtotal->subtract($costPrice->multiply($quantity));

            $result['quantity'] = (float)$quantity;
            $result['length'] = null;
            $result['total_meter'] = null;
            $result['subtotal_normal'] = (float)$subtotalNormal;
            $result['total_discount'] = (float)$totalDiscount;
            $result['subtotal'] = (float)$subtotal;
            $result['profit'] = (float)$profit;
        }

        return $result;
    }

    public function calculateSaleTotal($items)
    {
        $subtotalNormal = 0;
        $totalDiscount = 0;
        $grandTotal = 0;
        $totalProfit = 0;

        foreach ($items as $item) {
            $subtotalNormal += $item['subtotal_normal'] ?? 0;
            $totalDiscount += $item['total_discount'] ?? 0;
            $grandTotal += $item['subtotal'] ?? 0;
            $totalProfit += $item['profit'] ?? 0;
        }

        return [
            'subtotal_normal' => round($subtotalNormal, 2),
            'total_discount' => round($totalDiscount, 2),
            'grand_total' => round($grandTotal, 2),
            'total_profit' => round($totalProfit, 2),
        ];
    }
}
