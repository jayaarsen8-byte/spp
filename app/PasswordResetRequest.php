<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $table = 'password_reset_requests';
    protected $fillable = ['user_id', 'status', 'reviewed_by', 'rejection_reason', 'requested_at', 'reviewed_at'];
    protected $casts = ['requested_at' => 'datetime', 'reviewed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
