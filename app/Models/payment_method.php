<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class payment_method extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'payment_method';

    protected $fillable = [
        'title',
        'status',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'status',
            ]); // Log these attributes
    }
}
