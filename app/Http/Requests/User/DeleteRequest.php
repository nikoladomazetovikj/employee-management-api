<?php

namespace App\Http\Requests\User;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $companyId = $this->user()->company()->first()->pivot->company_id;
        $isManager = $this->user()->company()->first()->pivot->role_id;
        $employeeWorksInCompany = $this->user()->company()->first()->pivot->company_id;

        if (($isManager === Role::MANAGER->value) && ($employeeWorksInCompany === $companyId)) {
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
        return [
            //
        ];
    }
}
