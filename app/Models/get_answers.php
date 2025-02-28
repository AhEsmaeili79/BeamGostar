<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class get_answers extends Model
{

    use LogsActivity;
    protected $table = 'get_answers';

    protected $fillable = [
        'title',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'status',
            ]); // Log these attributes
    }
}
