<?php

// app/Models/Ad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Ad extends Model
{
    use HasFactory;
    use LogsActivity;

    // Specify which fields can be mass-assigned
    protected $fillable = ['title', 'description', 'image', 'url'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title','status',]); // Log these attributes
    }
}
