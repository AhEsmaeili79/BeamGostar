<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InvoiceRuleSet extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'title',
        'text',
        'state',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'text',
                'state',
            ]); // Log these attributes
    }
}
