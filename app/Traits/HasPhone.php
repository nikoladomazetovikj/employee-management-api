<?php

namespace App\Traits;

trait HasPhone
{
    public function PhoneValidationRules()
    {
        return [
            'phone.number' => 'required',
        ];
    }

    public function OptionalPhoneValidationRules()
    {
        return [
            'phone.number' => 'sometimes|required',
        ];
    }
}
