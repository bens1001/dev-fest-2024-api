<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'task_description' => $this->task_description,
            'user_id' => $this->user_id,
            'user_full_name' => $this->user->full_name,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
