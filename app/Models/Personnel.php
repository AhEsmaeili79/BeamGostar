<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;
    protected $table = 'personnel';

    protected $fillable = [
        'name',
        'family',
        'national_code',
        'user_id',
        'role_id',
    ];

    protected static function booted()
{
    static::created(function ($personnel) {
        // Generate username and password
        $username = $personnel->national_code;
        $password = bcrypt($personnel->national_code);

        // Create the User record
        $user = \App\Models\User::create([
            'name' => $username, // You can adjust this to any suitable username generation logic
            'password' => $password,
        ]);

        // Assign the selected role to the user
        if ($personnel->role_id) {
            $role = \Spatie\Permission\Models\Role::find($personnel->role_id);
            if ($role) {
                $user->assignRole($role); // Assign the role to the user
            }
        }

        // Assign the user_id to the personnel
        $personnel->user_id = $user->id;
        $personnel->save();
    });
}

    
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserRoles()
    {
        // Access roles through the user relation
        return $this->user ? $this->user->getRoleNames()->implode(', ') : 'No roles assigned';
    }
}
