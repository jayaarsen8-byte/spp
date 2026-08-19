<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['number', 'category_id', 'user_id', 'description', 'amount', 'expense_date'];
    protected $casts = ['amount' => 'decimal:2', 'expense_date' => 'date'];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
