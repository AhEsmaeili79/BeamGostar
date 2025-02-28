<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Personnel extends Model
{
    use HasFactory;
    use LogsActivity;
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
        static::saving(function ($personnel) {
            // Check if the record is being created (not updated)
            if ($personnel->isDirty() && !$personnel->exists) {
                // Check if the user already exists with the same national_code
                $user = \App\Models\User::where('name', $personnel->national_code)->first();

                // If no user exists, create one
                if (!$user) {
                    // Generate username and password
                    $username = $personnel->national_code;
                    $password = bcrypt($personnel->national_code);

                    // Create the User record
                    $user = \App\Models\User::create([
                        'name' => $username, // You can adjust this to any suitable username generation logic
                        'password' => $password,
                    ]);
                }

                // Assign the selected role to the user (if role_id is provided)
                if ($personnel->role_id) {
                    $role = \Spatie\Permission\Models\Role::find($personnel->role_id);
                    if ($role) {
                        $user->assignRole($role); // Assign the role to the user
                    }
                }

                // Assign the user_id to the personnel
                $personnel->user_id = $user->id;
            }
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'family',
                'national_code',
                'user_id',
                'role_id',
            ]); // Log these attributes
    }
}
