<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionRequest extends FormRequest
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
            'start_time' => 'sometimes|date|before:end_time',
            'end_time' => 'sometimes|date|after:start_time',
            'output_quantity' => 'sometimes|integer',
            'target_quantity' => 'sometimes|integer|gte:output_quantity',
        ];
    }
}
