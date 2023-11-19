<?php

namespace App\Http\Requests\Admin\Branch;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;

class BranchStoreUpdateRequest extends FormRequest
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
            'branch_name' => [
                'required',
                'string',
                'unique:'.(new Branch())->getTable().',name'.($this->get('branch_id') ? ','.$this->get('branch_id') : ''),
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
            'permission_name.unique' => 'A gudang name is exists',
        ];
    }
}
