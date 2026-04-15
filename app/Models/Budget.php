<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['amount', 'period', 'year', 'month', 'user_id'];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSpentAttribute()
    {
        $query = Transaction::where('user_id', $this->user_id)
            ->where('type', 'expense')
            ->whereYear('date', $this->year);

        if ($this->month) {
            $query->whereMonth('date', $this->month);
        }

        return $query->sum('amount');
    }

    public function getRemainingAttribute()
    {
        return $this->amount - $this->spent;
    }

    public function isExceeded()
    {
        return $this->spent > $this->amount;
    }
}
