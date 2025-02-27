<?php

namespace App\Observers;

use App\Models\Customers;
use App\Models\User;
class CustomerObserver
{
    /**
     * Handle the Customers "created" event.
     */
    public function created(Customers $customers): void
    {
        //
    }

    /**
     * Handle the Customers "updated" event.
     */
    public function updated(Customers $customers): void
    {
        //
    }

    /**
     * Handle the Customers "deleted" event.
     */
    public function deleted(Customers $customer)
    {
        // Check if there is a User record where name matches any of the customer fields
        $user = User::whereIn('name', [
            $customer->national_code,
            $customer->national_id,
            $customer->passport,
        ])->first();

        if ($user) {
            // If the user exists and their name matches, delete the User
            $user->delete();
        }
    }

    /**
     * Handle the Customers "restored" event.
     */
    public function restored(Customers $customers): void
    {
        //
    }

    /**
     * Handle the Customers "force deleted" event.
     */
    public function forceDeleted(Customers $customers): void
    {
        //
    }
}
