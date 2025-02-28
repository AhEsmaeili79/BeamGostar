<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Laboratory extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'laboratory';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    // This is a read-only model, preventing any updates or inserts
    public function save(array $options = [])
    {
        return false;
    }

    protected $fillable = [
        'id',
        'full_name_fa',
        'full_name_en',
        'national',
        'mobile',
        'customer_type',
        'clearing_type',
        'acceptance_date',
        'samples_number',
        'analyze_id',
        'tracking_code',
        'scan_form',
        'description',
        'priority',
        'status',
        'state',
        'date_success',
    ];

    // Relations
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }

    public function sample()
    {
        return $this->hasMany(Sample::class, 'customer_analysis_id', 'id');
    }

    public function financialCheck()
    {
        return $this->hasOne(FinancialCheck::class, 'customer_analysis_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'full_name_fa',
                'full_name_en',
                'national',
                'mobile',
                'customer_type',
                'clearing_type',
                'acceptance_date',
                'samples_number',
                'analyze_id',
                'tracking_code',
                'scan_form',
                'description',
                'priority',
                'status',
                'state',
                'date_success',
            ]); // Log these attributes
    }
}
