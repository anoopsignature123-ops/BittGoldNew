<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'reference_no',
        'payment_method',
        'payment_details',
        'admin_remark',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}