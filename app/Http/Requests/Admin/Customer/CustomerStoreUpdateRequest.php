<?php

namespace App\Http\Requests\Admin\Customer;

use App\Enums\CustomerTypeEnum;
use App\Enums\Preorder\MarketingEnum;
use App\Models\Area;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreUpdateRequest extends FormRequest
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
        $input = $this->all();

        return [
            'customer_name' => [
                'required',
                'string',
            ],
            'customer_email' => [
                'required',
                'email',
                Rule::unique((new User())->getTable(), 'email')->where(function ($query) use ($input) {
                    if ($input['customer_id']) {
                        $customer = Customer::withTrashed()->find($input['customer_id']);
                        if ($customer) {
                            $query->where('id', '!=', $customer->user_id);
                        }
                    }

                    $query->whereNull('deleted_at');

                    return $query;
                }),
            ],
            'customer_phone_number' => [
                'nullable',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
            'customer_company' => [
                'nullable',
                'string',
            ],
            'customer_password' => [
                'required_without:customer_id',
            ],
            'customer_type' => [
                'nullable',
                Rule::in([
                    CustomerTypeEnum::GENERAL,
                    CustomerTypeEnum::DISTRIBUTOR_CASH,
                ]),
            ],
            'customer_address' => [
                'nullable',
                'string',
            ],
            'customer_province_id' => [
                'nullable',
                Rule::exists((new Province())->getTable(), 'id'),
            ],
            'customer_regency_id' => [
                'nullable',
                Rule::exists((new Regency())->getTable(), 'id'),
            ],
            'customer_district_id' => [
                'nullable',
                Rule::exists((new District())->getTable(), 'id'),
            ],
            'customer_village_id' => [
                'nullable',
                Rule::exists((new Village())->getTable(), 'id'),
            ],
            // 'customer_target' => [
            //     'nullable',
            //     'numeric',
            //     'min:0',
            // ],
            'customer_schools.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'customer_area_id' => [
                'nullable',
            ],
            'customer_area_id.*' => [
                Rule::exists((new Area())->getTable(), 'id'),
            ],
            'customer_marketing' => [
                'nullable',
                Rule::in([
                    MarketingEnum::TEAM_A,
                    MarketingEnum::TEAM_B,
                    MarketingEnum::RETAIL,
                    MarketingEnum::WRITING,
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
            'customer_type.in' => 'Tipe konsumen tidak terdaftar',
        ];
    }
}
