<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidNationalCode implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the input is exactly 10 digits
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            return false;
        }
        
        return self::checkNationalCode($value);
    }

    public function message()
    {
        return __('filament.labels.national_code_invalid');
    }

    public static function checkNationalCode($code)
    {
        if (!preg_match('/^[0-9]{10}$/', $code)) {
            return false;
        }

        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^' . $i . '{10}$/', $code)) {
                return false;
            }
        }

        for ($i = 0, $sum = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        }

        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));

        return ($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity);
    }
}