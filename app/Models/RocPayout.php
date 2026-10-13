<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RocPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_id',
        'withdrawal_id',
        'installment_no',
        'amount',
        'status',
        'payout_date',
    ];

    protected $casts = [
        'payout_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function withdrawal()
    {
        return $this->belongsTo(Withdrawal::class);
    }
}
