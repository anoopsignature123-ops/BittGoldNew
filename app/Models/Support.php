<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'ticket_id', 'user_id', 'subject', 'category', 'priority', 'message', 'reply', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}