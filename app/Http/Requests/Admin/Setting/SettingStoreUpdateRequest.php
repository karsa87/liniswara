<?php

namespace App\Http\Requests\Admin\Setting;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class SettingStoreUpdateRequest extends FormRequest
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
            'setting_key' => [
                'required',
                'string',
                'unique:'.(new Setting())->getTable().',key'.($this->get('setting_id') ? ','.$this->get('setting_id') : ''),
            ],
            'setting_value' => [
                'required',
                'string',
            ],
            'setting_description' => [
                'nullable',
                'string',
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
            'setting_key.unique' => 'A key name is exists',
        ];
    }
}
