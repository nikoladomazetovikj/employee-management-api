<?php

namespace App\Http\Requests\User;

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
        $user = $this->user();
        $company = $user->company()->first();

        if ($user->id === $this->user->id) {
            return true;
        }

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
            'name' => 'sometimes|required',
            'surname' => 'sometimes|required',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            'date_of_birth' => ['sometimes', 'required', 'date'],
            'vacation_days' => 'required|sometimes',
        ];

        return array_merge($rules, $this->OptionalAddressValidationRules(), $this->OptionalPhoneValidationRules());
    }
}
