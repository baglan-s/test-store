<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['string', 'max:255', 'unique:products,slug'],
            'description' => ['string', 'max:255'],
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'specifications' => ['array'],
            'specifications.*.specification_id' => ['required', 'integer', 'exists:specifications,id'],
            'specifications.*.specification_value_id' => ['required', 'integer', 'exists:specification_values,id'],
        ];
    }
}
