<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyze extends Model
{
    use HasFactory;

    protected $table = 'analyze';

    protected $fillable = [
        'title',
        'status',
    ];
    public function analysisTimes()
    {
        return $this->hasMany(AnalysisTime::class, 'analyze_id');
    }

    public function analysisDelays()
    {
        return $this->hasMany(AnalysisDelay::class, 'analyze_id');
    }
    public function analysis_discount()
    {
        return $this->hasMany(analysis_discount::class, 'analyze_id');
    }
    public function priceAnalyses()
    {
        return $this->hasMany(price_analysis::class, 'analyze_id');
    }
}
