<?php

namespace App\Http\Resources;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $company = $this->company;
        $vacationDays = null;
        $roleId = null;
        $role = null;

        foreach ($company as $c) {
            $vacationDays = $c->pivot->vacation_days;
            $roleId = $c->pivot->role_id;
            $role = Role::getDescription($roleId);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'date_of_birth' => $this->date_of_birth,
            'email' => $this->email,
            'company' => CompanyResource::collection($this->company),
            'vacation_days' => $vacationDays,
            'role' => $role,
        ];
    }
}
