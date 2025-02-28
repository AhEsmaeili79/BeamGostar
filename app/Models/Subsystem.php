<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Subsystem extends Model

{
    use HasFactory;
    use LogsActivity;

    protected $table = 'subsystem';
    
    protected $fillable = [
        'title', 'title_en', 'icon_class', 'state', 'ordering', 'header_title',
    ];

    public $timestamps = false; // No `created_at`, only `updated_at`

    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class, 'subsystem_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
               'title', 'title_en', 'icon_class', 'state', 'ordering', 'header_title',
            ]); // Log these attributes
    }
}
