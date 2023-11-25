<?php

namespace App\Http\Requests\Admin\Customer;

use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerAddressStoreUpdateRequest extends FormRequest
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
            'customer_address_name' => [
                'required',
                'string',
            ],
            'customer_address_phone_number' => [
                'nullable',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
            'customer_address_customer_id' => [
                'required',
                Rule::exists((new Customer())->getTable(), 'id'),
            ],
            'customer_address_address' => [
                'nullable',
                'string',
            ],
            'customer_address_province_id' => [
                'nullable',
                Rule::exists((new Province())->getTable(), 'id'),
            ],
            'customer_address_regency_id' => [
                'nullable',
                Rule::exists((new Regency())->getTable(), 'id'),
            ],
            'customer_address_district_id' => [
                'nullable',
                Rule::exists((new District())->getTable(), 'id'),
            ],
            'customer_address_village_id' => [
                'nullable',
                Rule::exists((new Village())->getTable(), 'id'),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_address_is_default' => optional($this)->customer_address_is_default == 'on',
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_address_customer_id.exists' => 'Pelanggan tidak terdaftar',
        ];
    }
}
