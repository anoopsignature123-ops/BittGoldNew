<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['role_id', 'sponsor_id', 'referral_code', 'name', 'email', 'mobile', 'country_code', 'image', 'matched_bv', 'status', 'active_plan', 'activated_at', 'current_rank_no', 'current_rank_name', 'email_verified_at', 'password', 'plain_password', 'remember_token', 'deposit_wallet', 'earning_wallet', 'created_at', 'updated_at'])]

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activated_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(self::class, 'sponsor_id');
    }

    public function referrals()
    {
        return $this->hasMany(self::class, 'sponsor_id');
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function referralRecord()
    {
        return $this->hasOne(UserReferral::class);
    }
    // Relationships
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function kyc()
    {
        return $this->hasOne(Kyc::class);
    }
}