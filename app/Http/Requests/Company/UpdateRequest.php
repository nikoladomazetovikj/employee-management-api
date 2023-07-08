<?php

namespace App\Http\Requests\Company;

use App\Enums\Role;
use App\Traits\HasAddress;
use App\Traits\HasPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use HasAddress;
    use HasPhone;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $companyId = $this->user()->company()->first()->pivot->company_id;
        $isManager = $this->user()->company()->first()->pivot->role_id;

        if (($this->company->id === $companyId) && ($isManager === Role::MANAGER->value)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'name' => 'sometimes|required',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('companies', 'email')->ignore($this->company)
            ]
        ];

        return array_merge($rules, $this->OptionalAddressValidationRules(), $this->OptionalPhoneValidationRules());
    }
}
