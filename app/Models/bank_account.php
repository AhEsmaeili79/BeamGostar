<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class bank_account extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $table = 'bank_account';

    protected $fillable = [
        'account_type',
        'account_number',
        'card_number',
        'shaba_number',
        'account_holder_name',
    ];
    protected $casts = [
        'account_type' => 'integer',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'account_type',
                'account_number',
                'card_number',
                'shaba_number',
                'account_holder_name',
            ]); // Log these attributes
    }
}
