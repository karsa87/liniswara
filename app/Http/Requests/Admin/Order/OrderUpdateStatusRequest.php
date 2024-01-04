<?php

namespace App\Http\Requests\Admin\Order;

use App\Enums\Order\StatusEnum;
use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\MethodPaymentEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Models\Expedition;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class OrderUpdateStatusRequest extends FormRequest
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
            'order_resi' => [
                'nullable',
                'string',
                Rule::requiredIf($this->get('order_status') == StatusEnum::SENT && ! empty($this->get('order_expedition_id'))),
            ],
            'order_expedition_id' => [
                'nullable',
                Rule::exists((new Expedition())->getTable(), 'id'),
                Rule::requiredIf($this->get('order_status') == StatusEnum::SENT && ! empty($this->get('order_resi'))),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'order_expedition_id' => 'Ekspedisi',
        ];
    }

    // /**
    //  * Get the "after" validation callables for the request.
    //  *
    //  * @return array<int, Closure>
    //  */
    // public function after(): array
    // {
    //     // return [
    //     //     function (Validator $validator) {
    //     //         $order = Order::with('shipping')->find($this->order_id);

    //     //         if (
    //     //             $this->order_status == StatusEnum::SENT

    //     //         ) {
    //     //             $validator->errors()->add(
    //     //                 'status',
    //     //                 'Tidak bisa proses kirim tolong masukkan resi terlebih dahulu'
    //     //             );
    //     //         }
    //     //     },
    //     // ];
    // }
}
