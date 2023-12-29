<?php

namespace App\Http\Requests\Admin\Profile;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateEmailRequest extends FormRequest
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
        $user_id = auth()->user()->id;
        $rules = [
            'emailaddress' => [
                'required',
                Rule::unique((new User())->getTable(), 'email')->ignore($user_id),
            ],
            'confirmemailpassword' => [
                'required',
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
            'emailaddress' => 'Email',
            'confirmemailpassword' => 'Password',
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
                if ($this->confirmemailpassword) {
                    if (! Hash::check($this->confirmemailpassword, auth()->user()->password)) {
                        $validator->errors()->add(
                            'confirmemailpassword',
                            'Password salah'
                        );
                    }
                }
            },
        ];
    }
}
