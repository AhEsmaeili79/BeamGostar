<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class UploadAnswer extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $table = 'upload_answers';

    protected $fillable = ['customer_analysis_id', 'result'];

    public function customerAnalysis() {
        return $this->belongsTo(CustomerAnalysis::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
               'customer_analysis_id', 'result'
            ]); // Log these attributes
    }
}
