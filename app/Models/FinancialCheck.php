<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialCheck extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'financial_check';

    // Specify the fillable fields
    protected $fillable = [
        'customer_analysis_id',
        'scan_form',
        'state',
        'date_success',
        'deleted_at',
    ];

    /**
     * Define the relationship with the customer_analysis table.
     */
    public function customerAnalysis()
    {
        return $this->belongsTo(CustomerAnalysis::class);
    }
}
