<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InquireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'inquire_id' => $this->id,
            'user_id' => $this->user_id,
            'status_id' => $this->status_id,
            'type' => $this->type,
            'start' => $this->start,
            'end' => $this->end,
        ];
    }
}
