<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'name', 'password', 'role', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
