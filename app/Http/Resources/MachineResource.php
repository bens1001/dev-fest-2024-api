<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'machine_type' => $this->machine_type,
            'machine_name' => $this->machine_name,
            'status' => $this->status,
            'last_maintenance' => $this->last_maintenance,
            'first_usage' => $this->first_usage,
        ];
    }
}
