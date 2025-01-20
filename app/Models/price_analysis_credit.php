<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class price_analysis_credit extends Model
{
    use HasFactory;

    protected $table = 'price_analysis_credit';

    protected $fillable = [
        'analyze_id',
        'customers_id',
        'price',
        'date',
        'time',
        'deleted_at',
    ];

    // Define the relationship with the Analyze model
    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }
}
