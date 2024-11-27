<?php

namespace App\Http\Requests\Admin\Prerestock;

use Illuminate\Foundation\Http\FormRequest;

class PrerestockMigrateRequest extends FormRequest
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
            'prerestock_notes' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'prerestock_label' => [
                'required',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'prerestock_details' => [
                'required',
                'array',
                'min:1',
            ],
            'prerestock_details.*.product_id' => [
                'required',
                'numeric',
            ],
            'prerestock_details.*.qty' => [
                'required',
                'numeric',
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
            'prerestock_branch_id.exists' => 'Gudang tidak ditemukan',
            'prerestock_details.exists' => 'Kategori tidak ditemukan',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'prerestock_details' => 'Produk',
            'prerestock_details.*.product_id' => 'Detail Produk',
            'prerestock_details.*.qty' => 'Detail Product Kuantitas',
            'prerestock_details.*.type' => 'Detail Product Tipe',
        ];
    }
}
