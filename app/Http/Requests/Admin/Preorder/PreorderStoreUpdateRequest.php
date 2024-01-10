<?php

namespace App\Http\Requests\Admin\Preorder;

use App\Enums\Preorder\DiscountTypeEnum;
use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\MethodPaymentEnum;
use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\TaxEnum;
use App\Enums\Preorder\ZoneEnum;
use App\Models\Branch;
use App\Models\Collector;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Expedition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreorderStoreUpdateRequest extends FormRequest
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
            'preorder_date' => [
                'required',
                'date_format:"Y-m-d"',
            ],
            'preorder_branch_id' => [
                'required',
                Rule::exists((new Branch())->getTable(), 'id'),
            ],
            'preorder_collector_id' => [
                'required',
                Rule::exists((new Collector())->getTable(), 'id'),
            ],
            'preorder_customer_id' => [
                'required',
                Rule::exists((new Customer())->getTable(), 'id'),
            ],
            'preorder_customer_address_id' => [
                'required',
                Rule::exists((new CustomerAddress())->getTable(), 'id'),
            ],
            'preorder_status' => [
                'required',
                Rule::in([
                    StatusEnum::VALIDATION_ADMIN,
                    StatusEnum::PROCESS,
                    StatusEnum::SENT,
                    StatusEnum::DONE,
                ]),
            ],
            'preorder_status_payment' => [
                'required',
                Rule::in([
                    StatusPaymentEnum::NOT_PAID,
                    StatusPaymentEnum::PAID,
                    StatusPaymentEnum::DP,
                ]),
            ],
            'preorder_paid_at' => [
                'nullable',
                'date_format:"Y-m-d"',
                Rule::requiredIf($this->get('preorder_status_payment') == StatusPaymentEnum::PAID),
            ],
            'preorder_method_payment' => [
                'required',
                Rule::in([
                    MethodPaymentEnum::CASH,
                    MethodPaymentEnum::DEBIT,
                    MethodPaymentEnum::FREELANCE,
                ]),
            ],
            'preorder_marketing' => [
                'required',
                Rule::in([
                    MarketingEnum::TEAM_A,
                    MarketingEnum::TEAM_B,
                    MarketingEnum::RETAIL,
                    MarketingEnum::WRITING,
                ]),
            ],
            'preorder_tax' => [
                'required',
                Rule::in([
                    TaxEnum::NO_TAX,
                    TaxEnum::PPN_10,
                    TaxEnum::GST_6,
                    TaxEnum::VAT_20,
                ]),
            ],
            'preorder_zone' => [
                'required',
                Rule::in([
                    ZoneEnum::ZONE_1,
                    ZoneEnum::ZONE_2,
                ]),
            ],
            'preorder_notes' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'preorder_notes_staff' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'preorder_discount_type' => [
                'nullable',
                Rule::in([
                    DiscountTypeEnum::DISCOUNT_NO,
                    DiscountTypeEnum::DISCOUNT_PERCENTAGE,
                    DiscountTypeEnum::DISCOUNT_PRICE,
                ]),
            ],
            'preorder_discount_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'preorder_discount_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'preorder_resi' => [
                'nullable',
                'string',
                Rule::requiredIf(! empty($this->get('preorder_expedition_id'))),
            ],
            'preorder_expedition_id' => [
                'nullable',
                Rule::exists((new Expedition())->getTable(), 'id'),
                Rule::requiredIf(! empty($this->get('preorder_resi'))),
            ],
            'preorder_shipping_price' => [
                'nullable',
                'numeric',
                Rule::requiredIf(! empty($this->get('preorder_resi')) || ! empty($this->get('preorder_expedition_id'))),
            ],

            'preorder_details' => [
                'required',
                'array',
                'min:1',
            ],
            'preorder_details.*.product_id' => [
                'required',
                'numeric',
            ],
            'preorder_details.*.qty' => [
                'required',
                'numeric',
            ],
            'preorder_details.*.price' => [
                'required',
                'numeric',
            ],
            'preorder_details.*.discount_description' => [
                'nullable',
                'string',
            ],
            'preorder_details.*.discount' => [
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
            'preorder_branch_id.exists' => 'Gudang tidak ditemukan',
            'preorder_details.exists' => 'Kategori tidak ditemukan',
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
            'preorder_details' => 'Produk',
            'preorder_details.*.product_id' => 'Detail Produk',
            'preorder_details.*.qty' => 'Detail Product Kuantitas',
            'preorder_details.*.price' => 'Detail Product Harga',
            'preorder_paid_at' => 'Tanggal Pelunasan',
        ];
    }
}
