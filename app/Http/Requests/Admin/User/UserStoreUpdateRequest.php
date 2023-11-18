<?php

namespace App\Http\Requests\Admin\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreUpdateRequest extends FormRequest
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
            'user_name' => [
                'required',
                'string',
            ],
            'user_email' => [
                'required',
                'unique:'.(new User())->getTable().',email'.($this->get('user_id') ? ','.$this->get('user_id') : ''),
            ],
            'user_phone_number' => [
                'nullable',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
            'user_company' => [
                'nullable',
                'string',
            ],
            'user_role_id' => [
                'required',
                Rule::exists((new Role())->getTable(), 'id'),
            ],
            'user_password' => [
                'required_without:user_id',
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
            'user_role_id.exists' => 'A role is not found',
        ];
    }
}
