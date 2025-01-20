<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subsystem extends Model

{
    use HasFactory;

    protected $table = 'subsystem';
    
    protected $fillable = [
        'title', 'title_en', 'icon_class', 'state', 'ordering', 'header_title',
    ];

    public $timestamps = false; // No `created_at`, only `updated_at`

    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class, 'subsystem_id');
    }
    

}
