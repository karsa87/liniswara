<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class UpdatePasswordRequest extends FormRequest
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
            'currentpassword' => [
                'required',
            ],
            'password' => [
                'required',
                'confirmed',
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
            'currentpassword' => 'Password saat ini',
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     *
     * @return array<int, Closure>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->currentpassword) {
                    if (! Hash::check($this->currentpassword, auth()->user()->password)) {
                        $validator->errors()->add(
                            'currentpassword',
                            'Password salah'
                        );
                    }
                }
            },
        ];
    }
}
