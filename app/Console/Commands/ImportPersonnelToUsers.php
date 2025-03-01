<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
class ImportPersonnelToUsers extends Command
{
    protected $signature = 'import:personnel-to-users'; // This is how the command will be called
    protected $description = 'Import personnel data from personnel_new and create user entries in the users table';

    public function handle()
    {
        // Fetch all personnel records
        $personnelRecords = DB::table('personnel_new')->get();

        // Initialize counter for email numbering
        $counter = 1;

        foreach ($personnelRecords as $personnel) {
            // Generate user data
            $username = $personnel->national_code;
            $email = 'personnel' . $counter . '@gmail.com'; // Unique email
            $password = bcrypt($personnel->national_code); // Hash the national_code for password

            // Insert user data into the users table
            $user = DB::table('users')->insertGetId([
                'id' => $personnel->user_id,
                'name' => $username,
                'email' => $email,
                'password' => $password,
                'state' => 1,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'theme' => 'sunset',
                'theme_color' => null,
            ]);

            if ($personnel->role_id) {
                // Find the role based on role_id
                $role = Role::find($personnel->role_id);

                if ($role) {
                    // Assign the role to the user
                    $userModel = \App\Models\User::find($user); // Find the user by ID
                    $userModel->assignRole($role); // Assign the role to the user
                    $this->info("Assigned role {$role->name} to user: $username");
                } else {
                    $this->info("Role with ID {$personnel->role_id} not found for user: $username");
                }
            }

            // Increment the counter for the next email
            $counter++;

            $this->info("Imported user: $username with email: $email");
        }

        $this->info('Personnel data import completed!');
    }
}
