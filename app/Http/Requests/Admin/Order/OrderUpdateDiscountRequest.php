<?php

namespace App\Http\Requests\Admin\Order;

use App\Enums\Preorder\DiscountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateDiscountRequest extends FormRequest
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
            'order_shipping_price' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}
