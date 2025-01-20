<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class price_analysis extends Model
{
    use HasFactory;

    protected $table = 'price_analysis';

    protected $fillable = [
        'analyze_id',
        'price',
        'date',
        'time',
        'deleted_at'
    ];
    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }
}
