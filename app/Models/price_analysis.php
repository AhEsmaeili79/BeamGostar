<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class price_analysis extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'price_analysis';

    protected $fillable = [
        'analyze_id',
        'price',
        'date',
        'time',
        'deleted_at'
    ];
    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'analyze_id',
                'price',
                'date',
                'time',
                'deleted_at'
            ]); // Log these attributes
    }
}
