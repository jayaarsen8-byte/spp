<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'quantity', 'length', 'total_meter',
        'normal_unit_price', 'selling_unit_price', 'discount_per_unit',
        'cost_price', 'subtotal_normal', 'total_discount', 'subtotal', 'profit'
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'length' => 'decimal:4',
        'total_meter' => 'decimal:4',
        'normal_unit_price' => 'decimal:2',
        'selling_unit_price' => 'decimal:2',
        'discount_per_unit' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'subtotal_normal' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
