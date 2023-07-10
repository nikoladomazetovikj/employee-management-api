<?php

namespace App\Http\Requests\User;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
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
        return [
            //
        ];
    }
}
