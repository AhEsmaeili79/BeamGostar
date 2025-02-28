<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkAnalysisPerson extends Model
{
    use HasFactory;

    protected $table = 'link_analysis_persons';

    protected $fillable = [
        'personnel_id',
        'analyze_id',
        'date',
        'time',
        'deleted_at',
    ];
    // Defining the relationships
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }

    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }
}
