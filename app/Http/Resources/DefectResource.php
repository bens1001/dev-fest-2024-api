<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefectResource extends JsonResource
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
            'defect_type' => $this->defect_type,
            'defect_time' => $this->defect_time,
        ];
    }
}
