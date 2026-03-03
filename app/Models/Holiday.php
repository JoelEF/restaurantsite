<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['date', 'name', 'is_closed', 'open_time', 'close_time'];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];
}
