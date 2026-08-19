<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceivablePayment extends Model
{
    protected $fillable = ['receivable_id', 'user_id', 'amount', 'method', 'note', 'paid_at'];
    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime'];

    public function receivable()
    {
        return $this->belongsTo(Receivable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
