<?php

namespace App\Http\Requests\Admin\School;

use App\Models\School;
use Illuminate\Foundation\Http\FormRequest;

class SchoolStoreUpdateRequest extends FormRequest
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
            'school_name' => [
                'required',
                'string',
                'unique:'.(new School())->getTable().',name'.($this->get('school_id') ? ','.$this->get('school_id') : ''),
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
            'school_name.unique' => 'A gudang name is exists',
        ];
    }
}
