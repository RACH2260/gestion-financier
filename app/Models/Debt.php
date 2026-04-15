<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'creditor', 'description', 'amount', 'remaining', 'due_date',
        'start_date', 'type', 'interest', 'status', 'notes', 'user_id'
    ];

    protected $casts = [
        'due_date' => 'date',
        'start_date' => 'date',
        'amount' => 'decimal:2',
        'remaining' => 'decimal:2',
        'interest' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->amount - $this->remaining;
    }

    public function getProgressAttribute()
    {
        if ($this->amount == 0) return 0;
        return ($this->paid_amount / $this->amount) * 100;
    }
}
