<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'roc_amount',
        'activated_at',
        'status',
        'roc_payouts_count',
        'last_roc_payout_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rocPayouts()
    {
        return $this->hasMany(RocPayout::class);
    }
}