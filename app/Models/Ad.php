<?php

// app/Models/Ad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    // Specify which fields can be mass-assigned
    protected $fillable = ['title', 'description', 'image', 'url', 'user_id'];

    // Optional: Define a relationship to the user model if you want to link ads to users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
