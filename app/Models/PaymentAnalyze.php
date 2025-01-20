<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class PaymentAnalyze extends Model
{
    use HasFactory;

    protected $table = 'payment_analyze';

    protected $fillable = [
        'customer_analysis_id',
        'upload_fish',
        'transaction_id',
        'uniq_id',
        'datepay',
        'deleted_at',
    ];

    public function customerAnalysis()
    {
        return $this->belongsTo(CustomerAnalysis::class, 'customer_analysis_id');
    }
    public function setDatepayAttribute($value)
    {
        // Ensure the value is stored as a valid Persian date
        $this->attributes['datepay'] = Jalalian::fromFormat('Y/m/d', $value)->format('Y/m/d');
    }
    public function getDatepayAttribute($value)
    {
        return $value; // Already stored as Persian date
    }
}
