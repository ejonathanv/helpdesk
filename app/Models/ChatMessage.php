<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:d M, Y h:i a',
        'updated_at' => 'datetime:d M, Y h:i a',
    ];
}
