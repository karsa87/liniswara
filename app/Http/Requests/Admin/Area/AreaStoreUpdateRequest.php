<?php

namespace App\Http\Requests\Admin\Area;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;

class AreaStoreUpdateRequest extends FormRequest
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
            'area_name' => [
                'required',
                'string',
                'unique:'.(new Area())->getTable().',name'.($this->get('area_id') ? ','.$this->get('area_id') : ''),
            ],
            'area_target' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'area_name.unique' => 'A area name is exists',
        ];
    }
}