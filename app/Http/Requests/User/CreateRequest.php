<?php

namespace App\Http\Requests\User;

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
        $user = $this->user();
        $company = $user->company()->first();

        if ($company && $company->pivot) {
            $companyId = $company->pivot->company_id;
            $isManager = $company->pivot->role_id;

            if ($companyId !== null && $isManager === Role::MANAGER->value) {
                return true;
            }
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
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'date_of_birth' => ['required', 'date'],
            'vacation_days' => 'required',
        ];

        return array_merge($rules, $this->AddressValidationRules(), $this->PhoneValidationRules());
    }
}
