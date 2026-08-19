<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'customer_type', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function receivables()
    {
        return $this->hasMany(Receivable::class);
    }
}
