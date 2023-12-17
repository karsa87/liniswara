<?php

namespace App\Http\Requests\Admin\Preorder;

use App\Enums\Preorder\DiscountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreorderUpdateDiscountRequest extends FormRequest
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
            'preorder_shipping_price' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}
