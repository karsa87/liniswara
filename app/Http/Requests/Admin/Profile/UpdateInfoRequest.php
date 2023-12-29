<?php

namespace App\Http\Requests\Admin\Profile;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInfoRequest extends FormRequest
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
        $rules = [
            'profile_photo_id' => [
                'nullable',
                Rule::exists((new File())->getTable(), 'id'),
            ],
            'name' => [
                'required',
                'string',
            ],
            'company' => [
                'nullable',
                'string',
            ],
            'phone' => [
                'required',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'company' => 'Perusahaan',
            'phone' => 'Phone',
            'profile_photo_id' => 'Avatar',
        ];
    }
}
