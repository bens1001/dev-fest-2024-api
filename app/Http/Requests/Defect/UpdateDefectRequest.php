<?php

namespace App\Http\Requests\Defect;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDefectRequest extends FormRequest
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
            'defect_type' => ['sometimes', 'string', Rule::in(['mechanical', 'electrical', 'software'])],
            'defect_time' => 'sometimes|date|before:now',
        ];
    }
}
