<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class analysis_discount extends Model
{
    use SoftDeletes;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([ 'analyze_id','discount_type','cent','amount','date', 'time',]); // Log these attributes
    }
}
