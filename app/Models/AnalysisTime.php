<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisTime extends Model
{
    use HasFactory;

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
}
