<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'company', 'phone', 'address'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relations
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
