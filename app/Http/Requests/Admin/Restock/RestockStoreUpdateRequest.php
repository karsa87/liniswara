<?php

namespace App\Http\Requests\Admin\Restock;

use App\Enums\RestockTypeEnum;
use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestockStoreUpdateRequest extends FormRequest
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
            'restock_date' => [
                'required',
                'date_format:"Y-m-d"',
            ],
            'restock_branch_id' => [
                'required',
                Rule::exists((new Branch())->getTable(), 'id'),
            ],
            'restock_notes' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'restock_details' => [
                'required',
                'array',
                'min:1',
            ],
            'restock_details.*.product_id' => [
                'required',
                'numeric',
            ],
            'restock_details.*.qty' => [
                'required',
                'numeric',
            ],
            'restock_details.*.type' => [
                'required',
                Rule::in([
                    RestockTypeEnum::STOCK_ADD,
                    RestockTypeEnum::STOCK_MINUS,
                ]),
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
            'restock_branch_id.exists' => 'Gudang tidak ditemukan',
            'restock_details.exists' => 'Kategori tidak ditemukan',
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
            'restock_details' => 'Produk',
            'restock_details.*.product_id' => 'Detail Produk',
            'restock_details.*.qty' => 'Detail Product Kuantitas',
            'restock_details.*.type' => 'Detail Product Tipe',
        ];
    }
}
