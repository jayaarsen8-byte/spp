<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'unit_id', 'sku', 'barcode', 'name', 'description', 'image',
        'cost_price', 'consumer_price', 'applicator_price', 'buyer_price',
        'calculation_type', 'minimum_stock', 'is_active'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'consumer_price' => 'decimal:2',
        'applicator_price' => 'decimal:2',
        'buyer_price' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getPrice($type = 'consumer')
    {
        $priceMap = [
            'consumer' => 'consumer_price',
            'applicator' => 'applicator_price',
            'buyer' => 'buyer_price',
        ];
        return $this->{$priceMap[$type] ?? 'consumer_price'};
    }

    public function isLowStock()
    {
        return $this->stock && $this->stock->quantity <= $this->minimum_stock;
    }

    public function isOutOfStock()
    {
        return $this->stock && $this->stock->quantity <= 0;
    }
}
