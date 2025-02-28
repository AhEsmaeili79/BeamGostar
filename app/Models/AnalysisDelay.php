<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class AnalysisDelay extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'analysis_delay';

    protected $fillable = [
        'analyze_id', 
        'delay', 
        'text', 
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
                'delay', 
                'text', 
            ]); // Log these attributes
    }
}
