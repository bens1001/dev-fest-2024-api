<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnergyUsageResource extends JsonResource
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
            'machine_id' => $this->machine->id,
            'machine_name' => $this->machine->machine_name,
            'machine_type' => $this->machine->machine_type,
            'energy_consumed' => $this->energy_consumed,
            'start_shift_time' => $this->start_shift_time,
            'end_shift_time' => $this->end_shift_time,
        ];
    }
}
