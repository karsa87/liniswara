<?php

namespace App\Http\Requests\Admin\Category;

use App\Models\Category;
use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryStoreUpdateRequest extends FormRequest
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
            'category_name' => [
                'required',
                'string',
            ],
            'category_image_id' => [
                'nullable',
                Rule::exists((new File())->getTable(), 'id'),
            ],
            'category_parent_id' => [
                'nullable',
                Rule::exists((new Category())->getTable(), 'id'),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->name),
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
            'category_image_id.exists' => 'File tidak ditemukan',
            'category_parent_id.exists' => 'Parent kategori tidak ditemukan',
        ];
    }
}
