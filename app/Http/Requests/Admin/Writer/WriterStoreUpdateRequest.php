<?php

namespace App\Http\Requests\Admin\Writer;

use App\Models\Product;
use App\Models\Writer;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WriterStoreUpdateRequest extends FormRequest
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
        $id = $this->get('writer_id');

        return [
            'writer_name' => [
                'required',
                'string',
            ],
            'writer_email' => [
                'nullable',
                'email',
                Rule::unique((new Writer())->getTable(), 'email')->where(function (Builder $query) use ($id) {
                    if ($id) {
                        $query->where('id', $id);
                    }
                    $query->whereNull('deleted_at')->whereNotNull('email');

                    return $query;
                }),
            ],
            'writer_phone' => [
                'nullable',
                'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                'not_regex:/[a-zA-Z]/',
                'max:16',
            ],
            'writer_product_id' => [
                'nullable',
                Rule::exists((new Product())->getTable(), 'id'),
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
            'writer_email.email' => 'Email is wrong format',
            'writer_email.unique' => 'Email is exists',
            'writer_phone.regex' => 'Phone is not valid',
        ];
    }
}
