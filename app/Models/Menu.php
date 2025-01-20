<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'subsystem_id',
        'menu_id',
        'title',
        'title_en',
        'icon_class',
        'route',
        'state',
        'ordering',
        'clone_of',
        'open_in_blank',
        'open_in_iframe',
    ];

    public $timestamps = false; // Disable created_at by default

    protected $attributes = [
        'state' => 1,
        'open_in_blank' => 0,
        'open_in_iframe' => 0,
    ];

    protected $casts = [
        'state' => 'boolean',
        'open_in_blank' => 'boolean',
        'open_in_iframe' => 'boolean',
    ];

    // Define parent-child relationship
    public function subsystem(): BelongsTo
    {
        return $this->belongsTo(Subsystem::class, 'subsystem_id');
    }

    
}
