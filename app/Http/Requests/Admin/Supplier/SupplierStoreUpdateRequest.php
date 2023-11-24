<?php

namespace App\Http\Requests\Admin\Supplier;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierStoreUpdateRequest extends FormRequest
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
            'supplier_name' => [
                'required',
                'string',
            ],
            'supplier_email' => [
                'required',
                'unique:'.(new Supplier())->getTable().',email'.($this->get('supplier_id') ? ','.$this->get('supplier_id') : ''),
            ],
            'supplier_phone_number' => [
                'nullable',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
            'supplier_company' => [
                'nullable',
                'string',
            ],
            'supplier_address' => [
                'nullable',
                'string',
            ],
            'supplier_province_id' => [
                'nullable',
                Rule::exists((new Province())->getTable(), 'id'),
            ],
            'supplier_regency_id' => [
                'nullable',
                Rule::exists((new Regency())->getTable(), 'id'),
            ],
            'supplier_district_id' => [
                'nullable',
                Rule::exists((new District())->getTable(), 'id'),
            ],
            'supplier_village_id' => [
                'nullable',
                Rule::exists((new Village())->getTable(), 'id'),
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
            'supplier_province_id.exists' => 'Provinsi tidak ditemukan',
            'supplier_regency_id.exists' => 'Kota / Kabupaten tidak ditemukan',
            'supplier_district_id.exists' => 'Kecamatan tidak ditemukan',
            'supplier_village_id.exists' => 'Desa / Kelurahan tidak ditemukan',
        ];
    }
}
