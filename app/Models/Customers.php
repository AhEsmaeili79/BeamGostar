<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    // Defining constants for specific values
    const CUSTOMER_TYPE_HAGHIGHI = 0;  // حقیقی
    const CUSTOMER_TYPE_HOQQOQI = 1;  // حقوقی
    
    const CLEARING_TYPE_NAGHDI = 0;  // نقدی
    const CLEARING_TYPE_ETEBARI = 1; // اعتباری

    const NATIONALITY_IRANI = 0;  // ایرانی
    const NATIONALITY_KHAREJI = 1; // خارجی

    protected $table = 'customers';

    protected $fillable = [
        'customer_type',
        'clearing_type',
        'nationality',
        'national_code',
        'national_id',
        'passport',
        'economy_code',
        'name_fa',
        'family_fa',
        'name_en',
        'family_en',
        'birth_date',
        'password',
        're_password',
        'company_fa',
        'company_en',
        'mobile',
        'phone',
        'email',
        'postal_code',
        'address',
    ];

    protected static function booted()
    {
        static::created(function ($customer) {
            // Generate username and password
            $username = $customer->national_code ?? $customer->national_id ?? $customer->passport;
            $password = bcrypt($customer->password);
        
            // Create the User record
            $user = \App\Models\User::create([
                'name' => $username, // Or any suitable email logic
                'password' => $password,
            ]);
        
            // Assign the 'مشتریان' role
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'مشتریان']);
            $user->assignRole($role);
        
            // Assign the user_id to the customer
            $customer->user_id = $user->id;
            $customer->save();
        });
    }
    public function getFullNameFaAttribute()
    {
        // Ensure `name_fa` and `family_fa` are not null before concatenating
        return $this->name_fa . ' ' . $this->family_fa;
    }
    public function sendsms()
    {
        return $this->hasMany(sendsms::class, 'customers_id');
    }
    public function customerAnalysis()
    {
        return $this->hasOne(CustomerAnalysis::class, 'customers_id');
    }

}
