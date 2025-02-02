<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendSms extends Model
{
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
}
