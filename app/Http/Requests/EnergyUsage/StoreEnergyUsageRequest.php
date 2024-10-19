<?php

namespace App\Http\Requests\EnergyUsage;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnergyUsageRequest extends FormRequest
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
            'energy_consumed' => 'required|numeric',
            'start_shift_time' => 'required|date',
            'end_shift_time' => 'required|date|after:start_shift_time',
        ];
    }
}
