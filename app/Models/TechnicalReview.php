<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalReview extends Model
{
    use HasFactory;

    protected $table = 'technical_reviews';

    protected $fillable = [
        'customer_analysis_id',
        'analyze_id',
        'state',
        'text',
        'deleted_at',
    ];

    // Define the relationship with CustomerAnalysis
    public function customerAnalysis()
    {
        return $this->belongsTo(CustomerAnalysis::class);
    }

    /**
     * Get the analyze associated with the sample.
     */
    public function analyze()
    {
        return $this->belongsTo(Analyze::class);
    }
}
