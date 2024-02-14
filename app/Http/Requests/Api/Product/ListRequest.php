<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListRequest extends FormRequest
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
            'q' => [
                'nullable',
                'string',
                'alpha_num',
            ],
            'limit' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'category_id' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'order_column' => [
                'nullable',
                'string',
                'alpha_num',
            ],
            'order_sort' => [
                'nullable',
                'string',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'q' => 'Search',
        ];
    }
}
