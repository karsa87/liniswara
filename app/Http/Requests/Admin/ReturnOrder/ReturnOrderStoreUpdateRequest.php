<?php

namespace App\Http\Requests\Admin\ReturnOrder;

use App\Enums\Preorder\DiscountTypeEnum;
use App\Enums\Preorder\TaxEnum;
use App\Enums\ReturnOrder\StatusEnum;
use App\Models\Branch;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReturnOrderStoreUpdateRequest extends FormRequest
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
            'return_order_id' => [
                'required',
                Rule::exists((new Order())->getTable(), 'id'),
            ],
            'return_date' => [
                'required',
                'date_format:"Y-m-d"',
            ],
            'return_branch_id' => [
                'required',
                Rule::exists((new Branch())->getTable(), 'id'),
            ],
            'return_status' => [
                'required',
                Rule::in([
                    StatusEnum::NEW,
                    StatusEnum::CONFIRMATION,
                    StatusEnum::CANCELLED,
                ]),
            ],
            'return_tax' => [
                'nullable',
                Rule::in([
                    TaxEnum::NO_TAX,
                    TaxEnum::PPN_10,
                    TaxEnum::GST_6,
                    TaxEnum::VAT_20,
                ]),
            ],
            'return_notes' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'return_discount_type' => [
                'nullable',
                Rule::in([
                    DiscountTypeEnum::DISCOUNT_NO,
                    DiscountTypeEnum::DISCOUNT_PERCENTAGE,
                    DiscountTypeEnum::DISCOUNT_PRICE,
                ]),
            ],
            'return_discount_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'return_discount_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'return_shipping_price' => [
                'nullable',
                'numeric',
                Rule::requiredIf(! empty($this->get('return_resi')) || ! empty($this->get('return_expedition_id'))),
            ],

            'return_details' => [
                'required',
                'array',
                'min:1',
            ],
            'return_details.*.order_detail_id' => [
                'required',
                'numeric',
            ],
            'return_details.*.product_id' => [
                'required',
                'numeric',
            ],
            'return_details.*.qty' => [
                'required',
                'numeric',
            ],
            'return_details.*.price' => [
                'required',
                'numeric',
            ],
            'return_details.*.discount_description' => [
                'nullable',
                'string',
            ],
            'return_details.*.discount' => [
                'nullable',
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
            'return_branch_id.exists' => 'Gudang tidak ditemukan',
            'return_details.exists' => 'Product tidak ditemukan',
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
            'return_order_id' => 'Pesanan',
            'return_details' => 'Produk',
            'return_details.*.product_id' => 'Detail Produk',
            'return_details.*.qty' => 'Detail Product Kuantitas',
            'return_details.*.price' => 'Detail Product Harga',
        ];
    }
}
