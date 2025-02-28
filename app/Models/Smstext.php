<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smstext extends Model
{
    use HasFactory;

    protected $table = 'smstext';

    protected $fillable = [
        'stage_level',
        'text',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
