<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Smstext extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'smstext';

    protected $fillable = [
        'stage_level',
        'text',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
               'stage_level',
                'text',
                'status',
            ]); // Log these attributes
    }
}
