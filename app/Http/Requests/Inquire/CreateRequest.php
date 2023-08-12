<?php

namespace App\Http\Requests\Inquire;

use App\Enums\InquireType;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateRequest extends FormRequest
{
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

            if ($companyId !== null && $isManager === Role::EMPLOYEE->value) {
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
        return [
            'type' => ['required', new Enum(InquireType::class)],
            'start' => ['required', 'date'],
            'end' => ['required', 'date',  'after_or_equal:start'],
        ];
    }
}
