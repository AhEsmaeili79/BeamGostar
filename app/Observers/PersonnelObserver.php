<?php

namespace App\Observers;

use App\Models\Personnel;
use App\Models\User;

class PersonnelObserver
{
    /**
     * Handle the Personnel "created" event.
     */
    public function created(Personnel $personnel): void
    {
        //
    }

    /**
     * Handle the Personnel "updated" event.
     */
    public function updated(Personnel $personnel): void
    {
        //
    }

    /**
     * Handle the Personnel "deleted" event.
     */
    public function deleted(Personnel $personnel)
    {
        // Check if there is a User record where name matches national_code
        $user = User::where('name', $personnel->national_code)->first();

        if ($user) {
            // If found, delete the related User record
            $user->delete();
        }
    }

    /**
     * Handle the Personnel "restored" event.
     */
    public function restored(Personnel $personnel): void
    {
        //
    }

    /**
     * Handle the Personnel "force deleted" event.
     */
    public function forceDeleted(Personnel $personnel): void
    {
        //
    }
}
