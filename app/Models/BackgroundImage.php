<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class BackgroundImage extends Model
{
    use HasFactory;

    protected $table = 'background_images';

    protected $fillable = ['image_path'];
}
