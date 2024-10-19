<?php

namespace App\Http\Requests\Machine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMachineRequest extends FormRequest
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
            'machine_type' => ['required','string',Rule::in(['Welding Robot', 'Stamping Press', 'Painting Robot', 'Automated Guided Vehicle', 'CNC Machine', 'Leak Test Machine']),],
            'machine_name' => 'required|string',
            'status' => ['required','string',Rule::in(['running', 'idle', 'maintenance']),],
            'last_maintenance' => 'required|date|before:now',
            'first_usage' => 'required|date|before:now',
        ];
    }
}
