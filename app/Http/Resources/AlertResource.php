<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
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
            'alert_message' => $this->alert_message,
            'alert_time' => $this->alert_time,
        ];
    }
}
