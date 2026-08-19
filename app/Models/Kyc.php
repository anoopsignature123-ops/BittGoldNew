<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $table = 'kycs';

    protected $fillable = [
        'user_id',
        'pan_number',
        'pan_photo',
        'aadhaar_number',
        'aadhaar_front_photo',
        'aadhaar_back_photo',
        'bank_name',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'bank_proof_photo',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
