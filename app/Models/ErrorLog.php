<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'error_code',
        'message',
        'ip_address',
        'user_agent',
        'referer',
        'hit_count',
        'last_checked_status',
        'last_checked_at',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];
}
