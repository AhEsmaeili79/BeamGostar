<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InvoiceSet extends Model
{
    use HasFactory;
    use LogsActivity;
    // The table associated with the model.
    protected $table = 'invoice_set';

    // The attributes that are mass assignable.
    protected $fillable = [
        'max_day',
        'created_at',
        'updated_at',
    ];

    // The attributes that should be mutated to dates.
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define a mutator to handle 'created_at' and 'updated_at' as date
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'max_day',
                'created_at',
                'updated_at',
            ]); // Log these attributes
    }
}
