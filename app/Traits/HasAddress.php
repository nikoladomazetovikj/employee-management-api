<?php

namespace App\Traits;

trait HasAddress
{
    public function AddressValidationRules()
    {
        return [
            'address.street' => 'required',
            'address.city' => 'required',
            'address.zip' => 'required',
            'address.state' => 'required',
            'address.country' => 'required',
        ];
    }

    public function OptionalAddressValidationRules()
    {
        return [
            'address.street' => 'sometimes|required',
            'address.city' => 'sometimes|required',
            'address.zip' => 'sometimes|required',
            'address.state' => 'sometimes|required',
            'address.country' => 'sometimes|required',
        ];
    }

}
