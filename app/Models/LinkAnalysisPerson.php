<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkAnalysisPerson extends Model
{
    use HasFactory;

    protected $table = 'link_analysis_persons';

    protected $fillable = [
        'customers_id',
        'analyze_id',
        'date',
        'time',
        'deleted_at',
    ];
    // Defining the relationships
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }

    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }
}
