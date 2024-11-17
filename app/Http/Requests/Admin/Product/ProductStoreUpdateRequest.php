<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Category;
use App\Models\File;
use App\Models\School;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductStoreUpdateRequest extends FormRequest
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
            'product_code' => [
                'required',
                'string',
            ],
            'product_name' => [
                'required',
                'string',
            ],
            'product_description' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'product_stock' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_price_zone_2' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_price_zone_3' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_price_zone_4' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_price_zone_5' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_price_zone_6' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_discount_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'product_discount_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'product_is_best_seller' => [
                'nullable',
                'boolean',
            ],
            'product_is_recommendation' => [
                'nullable',
                'boolean',
            ],
            'product_is_new' => [
                'nullable',
                'boolean',
            ],
            'product_is_special_discount' => [
                'nullable',
                'boolean',
            ],
            'product_is_active' => [
                'nullable',
                'boolean',
            ],
            'product_thumbnail_id' => [
                'nullable',
                Rule::exists((new File())->getTable(), 'id'),
            ],
            'product_category_id' => [
                'nullable',
                Rule::exists((new Category())->getTable(), 'id'),
            ],
            'product_school_id' => [
                'nullable',
                Rule::exists((new School())->getTable(), 'id'),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->get('product_name', '')),
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
            'product_thumbnail_id.exists' => 'File tidak ditemukan',
            'product_category_id.exists' => 'Kategori tidak ditemukan',
        ];
    }
}
