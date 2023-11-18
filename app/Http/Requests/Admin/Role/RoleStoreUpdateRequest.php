<?php

namespace App\Http\Requests\Admin\Role;

use App\Models\Permission;
use App\Models\Role;
use Closure;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RoleStoreUpdateRequest extends FormRequest
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
            'role_name' => [
                'required',
                'string',
            ],
            'role_description' => [
                'nullable',
                'string',
            ],
            'slug' => [
                'required',
                'unique:'.(new Role())->getTable().',slug'.($this->get('role_id') ? ','.$this->get('role_id') : ''),
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->role_name),
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.unique' => 'A role name is exists',
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
                $permissionKeys = array_keys($this->permissions);
                $permissions = Permission::select('id', 'key')->whereIn('key', $permissionKeys)->get()->keyBy('key');
                $notFounds = Arr::except($this->permissions, $permissions->keys()->toArray());
                if ($notFounds) {
                    $validator->errors()->add(
                        'permissions',
                        'Permission not found: '.Arr::join($notFounds, ',')
                    );
                }
            },
        ];
    }
}
