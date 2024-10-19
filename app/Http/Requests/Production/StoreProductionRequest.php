<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionRequest extends FormRequest
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
            'start_time' => 'required|date|date_format:Y-m-d H:i:s|before:end_time',
            'end_time' => 'required|date|date_format:Y-m-d H:i:s|after:start_time',
            'output_quantity' => 'required|integer',
            'target_quantity' => 'required|integer|gte:output_quantity',
        ];
    }
}
