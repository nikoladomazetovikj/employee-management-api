<?php

namespace App\Http\Requests\Inquire;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $isManager = $this->user()->company()->first()->pivot->role_id;

        if ($isManager === Role::MANAGER->value) {
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
            'status_id' => 'required'
        ];
    }
}
