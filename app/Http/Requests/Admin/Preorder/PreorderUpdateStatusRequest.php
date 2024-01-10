<?php

namespace App\Http\Requests\Admin\Preorder;

use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\MethodPaymentEnum;
use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreorderUpdateStatusRequest extends FormRequest
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
        ];
    }

    public function attributes()
    {
        return [
            'preorder_expedition_id' => 'Ekspedisi',
            'preorder_paid_at' => 'Tanggal Pelunasan',
        ];
    }
}
