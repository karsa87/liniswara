<?php

namespace App\Http\Requests\Admin\Area;

use App\Models\Area;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreaStoreUpdateRequest extends FormRequest
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
            'area_name' => [
                'required',
                'string',
                'unique:'.(new Area())->getTable().',name'.($this->get('area_id') ? ','.$this->get('area_id') : ''),
            ],
            'area_schools.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'area_province_id' => [
                'nullable',
                Rule::exists((new Province())->getTable(), 'id'),
            ],
            'area_regency_id' => [
                'nullable',
                Rule::exists((new Regency())->getTable(), 'id'),
            ],
            'area_district_id' => [
                'nullable',
                Rule::exists((new District())->getTable(), 'id'),
            ],
            'area_village_id' => [
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
            'area_name.unique' => 'A area name is exists',
            'area_province_id.exists' => 'Provinsi tidak ditemukan',
            'area_regency_id.exists' => 'Kota / Kabupaten tidak ditemukan',
            'area_district_id.exists' => 'Kecamatan tidak ditemukan',
            'area_village_id.exists' => 'Desa / Kelurahan tidak ditemukan',
        ];
    }
}
