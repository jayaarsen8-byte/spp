<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $fillable = [
        'sale_id', 'customer_id', 'total_amount', 'paid_amount',
        'remaining_amount', 'due_date', 'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(ReceivablePayment::class);
    }
}
