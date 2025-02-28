<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class sendsms extends Model
{

    use HasFactory;
    use LogsActivity;

    protected $table = 'sendsms';

    protected $fillable = [
        'customers_id',
        'number',
        'text',
        'state',
        'send_time',
    ];

    /**
     * Define the relationship with the Customer model.
     */
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'customers_id',
                'number',
                'text',
                'state',
                'send_time',
            ]); // Log these attributes
    }
}
