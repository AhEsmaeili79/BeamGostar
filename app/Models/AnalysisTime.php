<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class AnalysisTime extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'analysis_time';

    protected $fillable = [
        'analyze_id', 
        'accordingto', 
        'number_done', 
        'number_minutes', 
        'default_number_day',
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
                'accordingto', 
                'number_done', 
                'number_minutes', 
                'default_number_day',
            ]); // Log these attributes
    }
}
