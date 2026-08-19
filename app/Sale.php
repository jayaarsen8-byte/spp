<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 'customer_id', 'user_id', 'price_type',
        'subtotal_normal', 'total_discount', 'grand_total',
        'payment_amount', 'change_amount', 'receivable_amount', 'status', 'sold_at'
    ];

    protected $casts = [
        'subtotal_normal' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'receivable_amount' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receivable()
    {
        return $this->hasOne(Receivable::class);
    }
}
