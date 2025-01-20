<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_analysis_id',
        'analyze_id',
        'sample_code',
        'order',
        'status',
        'deleted_at',
    ];

    /**
     * Get the customer analysis associated with the sample.
     */
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
