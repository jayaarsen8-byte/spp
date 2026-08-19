<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'model', 'model_id', 'changes', 'description', 'ip_address', 'user_agent'];
    protected $casts = ['changes' => 'json'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
