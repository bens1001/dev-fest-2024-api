<?php

namespace App\Http\Requests\Defect;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDefectRequest extends FormRequest
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
            'defect_type' => ['required', 'string', Rule::in(['mechanical', 'electrical', 'software'])],
            'defect_time' => 'required|date|date_format:Y-m-d H:i:s|before:now',
        ];
    }
}
