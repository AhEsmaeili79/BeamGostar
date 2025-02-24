<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'customer_analysis_id',
        'status',
        'rejection_reason',
    ];

    public function customerAnalysis()
    {
        return $this->belongsTo(CustomerAnalysis::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customers_id'); // Ensure 'customers_id' is the correct foreign key
    }
    
}

