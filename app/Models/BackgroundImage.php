<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class BackgroundImage extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'background_images';

    protected $fillable = ['image_path'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'background_images', 
            ]); // Log these attributes
    }
}
