<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_no',
        'wallet_type',
        'type',
        'amount',
        'remark',
    ];

    protected $appends = ['transaction_no'];

    protected static function booted()
    {
        static::created(function (self $transaction) {
            if (!$transaction->transaction_no) {
                $transaction->transaction_no = '#TRX' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT);
                $transaction->saveQuietly();
            }
        });
    }

    public function getTransactionNoAttribute()
    {
        if (!empty($this->attributes['transaction_no'])) {
            return $this->attributes['transaction_no'];
        }

        if (!$this->id) {
            return null;
        }

        return '#TRX' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}