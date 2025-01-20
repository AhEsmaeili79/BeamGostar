<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAnalysis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_analysis';

    protected $fillable = [
        'customers_id',
        'acceptance_date',
        'get_answers_id',
        'analyze_id',
        'samples_number',
        'analyze_time',
        'value_added',
        'grant',
        'additional_cost',
        'additional_cost_text',
        'total_cost',
        'applicant_share',
        'network_share',
        'network_id',
        'payment_method_id',
        'discount',
        'discount_num',
        'scan_form',
        'description',
        'priority',
        'status',
        'tracking_code',
        'date_answer',
        'upload_answer',
        'deleted_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }

    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(payment_method::class, 'payment_method_id');
    }

    public function getAnswers()
    {
        return $this->belongsTo(get_answers::class, 'get_answers_id');
    }
    public function customerAnalyses()
    {
        return $this->hasMany(CustomerAnalysis::class, 'customers_id');
    }
    public function paymentAnalyzes()
    {
        return $this->hasMany(PaymentAnalyze::class, 'customer_analysis_id');
    }
}
