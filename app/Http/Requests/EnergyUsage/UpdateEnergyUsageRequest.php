<?php

namespace App\Http\Requests\EnergyUsage;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnergyUsageRequest extends FormRequest
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
            'energy_consumed' => 'sometimes|numeric',
            'start_shift_time' => 'sometimes|date|before:end_shift_time',
            'end_shift_time' => 'sometimes|date|after:start_shift_time',
        ];
    }
}
