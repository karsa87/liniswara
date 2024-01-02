<?php

namespace App\Http\Requests\Admin\Collector;

use App\Models\Collector;
use App\Models\District;
use App\Models\File;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CollectorStoreUpdateRequest extends FormRequest
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
            'collector_name' => [
                'required',
                'string',
            ],
            'collector_email' => [
                'nullable',
                'email',
                'unique:'.(new Collector())->getTable().',email'.($this->get('collector_id') ? ','.$this->get('collector_id') : ''),
            ],
            'collector_phone_number' => [
                'required',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
            'collector_npwp' => [
                'nullable',
                'string',
            ],
            'collector_gst' => [
                'nullable',
                'string',
            ],
            'collector_company' => [
                'nullable',
                'string',
            ],
            'collector_address' => [
                'nullable',
                'string',
            ],
            'collector_footer' => [
                'nullable',
                'string',
            ],
            'collector_billing_notes' => [
                'nullable',
                'string',
            ],
            'collector_province_id' => [
                'nullable',
                Rule::exists((new Province())->getTable(), 'id'),
            ],
            'collector_regency_id' => [
                'nullable',
                Rule::exists((new Regency())->getTable(), 'id'),
            ],
            'collector_district_id' => [
                'nullable',
                Rule::exists((new District())->getTable(), 'id'),
            ],
            'collector_village_id' => [
                'nullable',
                Rule::exists((new Village())->getTable(), 'id'),
            ],
            'collector_signin_file_id' => [
                'nullable',
                Rule::exists((new File())->getTable(), 'id'),
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
            'collector_province_id.exists' => 'Provinsi tidak ditemukan',
            'collector_regency_id.exists' => 'Kota / Kabupaten tidak ditemukan',
            'collector_district_id.exists' => 'Kecamatan tidak ditemukan',
            'collector_village_id.exists' => 'Desa / Kelurahan tidak ditemukan',
            'collector_signin_file_id.exists' => 'File cap tidak ditemukan',
        ];
    }
}
