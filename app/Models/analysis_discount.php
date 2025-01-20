<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class analysis_discount extends Model
{
    use SoftDeletes;

    protected $table = 'analysis_discount';

    protected $fillable = [
        'analyze_id',
        'discount_type',
        'cent',
        'amount',
        'date',
        'time',
    ];

    public function analyze()
    {
        return $this->belongsTo(Analyze::class);
    }
}
