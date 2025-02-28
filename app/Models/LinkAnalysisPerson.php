<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LinkAnalysisPerson extends Model
{
    use HasFactory;
    use LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'personnel_id',
                'analyze_id',
                'date',
                'time',
                'deleted_at',
            ]); // Log these attributes
    }
}
