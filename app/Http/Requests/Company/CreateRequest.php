<?php

namespace App\Http\Requests\Company;

use App\Enums\Role;
use App\Traits\HasAddress;
use App\Traits\HasPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    use HasAddress;
    use HasPhone;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('companies', 'email')]
        ];

        return array_merge($rules, $this->AddressValidationRules(), $this->PhoneValidationRules() );
    }
}
