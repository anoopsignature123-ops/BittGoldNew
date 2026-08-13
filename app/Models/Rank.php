<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'rank_no',
        'name',
        'power_leg_target',
        'weaker_leg_target',
        'monthly_bonus',
    ];
}