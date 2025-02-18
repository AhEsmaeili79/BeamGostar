<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisDelay extends Model
{
    use HasFactory;

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
}
