<?php

namespace App\Http\Requests\Machine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMachineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'machine_name' => 'sometimes|string',
            'status' => ['sometimes', 'string', Rule::in(['running', 'idle', 'maintenance']),],
            'last_maintenance' => 'sometimes|date|date_format:Y-m-d H:i:s|before:now|after:first_usage',
            'first_usage' => 'sometimes|date|date_format:Y-m-d H:i:s|before:now|before:last_maintenance',
        ];
    }
}
