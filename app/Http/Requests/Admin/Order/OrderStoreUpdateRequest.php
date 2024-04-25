<?php

namespace App\Http\Requests\Admin\Order;

use App\Enums\Order\StatusEnum;
use App\Enums\Preorder\DiscountTypeEnum;
use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\MethodPaymentEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\TaxEnum;
use App\Enums\Preorder\ZoneEnum;
use App\Models\Area;
use App\Models\Branch;
use App\Models\Collector;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Expedition;
use App\Models\Order;
use App\Models\Preorder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class OrderStoreUpdateRequest extends FormRequest
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
            'order_preorder_id' => [
                'required',
                Rule::exists((new Preorder())->getTable(), 'id'),
            ],
            'order_date' => [
                'required',
                'date_format:"Y-m-d"',
            ],
            'order_branch_id' => [
                'required',
                Rule::exists((new Branch())->getTable(), 'id'),
            ],
            'order_collector_id' => [
                'required',
                Rule::exists((new Collector())->getTable(), 'id'),
            ],
            'order_customer_id' => [
                'required',
                Rule::exists((new Customer())->getTable(), 'id'),
            ],
            'order_customer_address_id' => [
                'required',
                Rule::exists((new CustomerAddress())->getTable(), 'id'),
            ],
            'order_area_id' => [
                'nullable',
                Rule::exists((new Area())->getTable(), 'id'),
            ],
            'order_status' => [
                'required',
                Rule::in([
                    StatusEnum::VALIDATION_ADMIN,
                    StatusEnum::PROCESS,
                    StatusEnum::PACKING,
                    StatusEnum::CANCEL,
                    StatusEnum::SENT,
                    StatusEnum::DONE,
                ]),
            ],
            'order_status_payment' => [
                'required',
                Rule::in([
                    StatusPaymentEnum::NOT_PAID,
                    StatusPaymentEnum::PAID,
                    StatusPaymentEnum::DP,
                ]),
            ],
            'order_paid_at' => [
                'nullable',
                'date_format:"Y-m-d"',
                Rule::requiredIf($this->get('order_status_payment') == StatusPaymentEnum::PAID),
            ],
            'order_method_payment' => [
                'required',
                Rule::in([
                    MethodPaymentEnum::CASH,
                    MethodPaymentEnum::DEBIT,
                    MethodPaymentEnum::FREELANCE,
                ]),
            ],
            'order_marketing' => [
                'required',
                Rule::in([
                    MarketingEnum::TEAM_A,
                    MarketingEnum::TEAM_B,
                    MarketingEnum::RETAIL,
                    MarketingEnum::WRITING,
                ]),
            ],
            'order_tax' => [
                'required',
                Rule::in([
                    TaxEnum::NO_TAX,
                    TaxEnum::PPN_10,
                    TaxEnum::GST_6,
                    TaxEnum::VAT_20,
                ]),
            ],
            'order_zone' => [
                'required',
                Rule::in([
                    ZoneEnum::ZONE_1,
                    ZoneEnum::ZONE_2,
                ]),
            ],
            'order_notes' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'order_notes_staff' => [
                'nullable',
                'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
            ],
            'order_discount_type' => [
                'nullable',
                Rule::in([
                    DiscountTypeEnum::DISCOUNT_NO,
                    DiscountTypeEnum::DISCOUNT_PERCENTAGE,
                    DiscountTypeEnum::DISCOUNT_PRICE,
                ]),
            ],
            'order_discount_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'order_discount_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'order_resi' => [
                'nullable',
                'string',
                Rule::requiredIf(! empty($this->get('order_expedition_id'))),
            ],
            'order_expedition_id' => [
                'nullable',
                Rule::exists((new Expedition())->getTable(), 'id'),
                Rule::requiredIf(! empty($this->get('order_resi'))),
            ],
            'order_shipping_price' => [
                'nullable',
                'numeric',
                Rule::requiredIf(! empty($this->get('order_resi')) || ! empty($this->get('order_expedition_id'))),
            ],

            'order_details' => [
                'required',
                'array',
                'min:1',
            ],
            'order_details.*.preorder_detail_id' => [
                'required',
                'numeric',
            ],
            'order_details.*.product_id' => [
                'required',
                'numeric',
            ],
            'order_details.*.qty' => [
                'required',
                'numeric',
            ],
            'order_details.*.price' => [
                'required',
                'numeric',
            ],
            'order_details.*.discount_description' => [
                'nullable',
                'string',
            ],
            'order_details.*.discount' => [
                'nullable',
                'numeric',
            ],
            'order_is_exclude_target' => [
                'nullable',
                'boolean',
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
            'order_branch_id.exists' => 'Gudang tidak ditemukan',
            'order_details.exists' => 'Kategori tidak ditemukan',
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
            'order_details' => 'Produk',
            'order_details.*.product_id' => 'Detail Produk',
            'order_details.*.qty' => 'Detail Product Kuantitas',
            'order_details.*.price' => 'Detail Product Harga',
            'order_paid_at' => 'Tanggal Pelunasan',
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
                $order = Order::with('shipping')->find($this->order_id);

                if (
                    $this->order_status == StatusEnum::SENT
                    && is_null($order->shipping)
                ) {
                    $validator->errors()->add(
                        'status',
                        'Tidak bisa proses kirim tolong masukkan resi terlebih dahulu'
                    );
                }
            },
        ];
    }
}
