<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',       // Add 'url' to the fillable array
        'error_code',
        'message',
    ];
}
