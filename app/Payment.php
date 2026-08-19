<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['sale_id', 'method', 'amount', 'note'];
    protected $casts = ['amount' => 'decimal:2'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
