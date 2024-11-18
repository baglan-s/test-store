<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderCreateRequest extends FormRequest
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
            'cart_id' => Rule::requiredIf(fn () => !Auth::user()), 'integer',
            'customer_email' => Rule::requiredIf(fn () => !Auth::user()), 'string',
            'customer_phone' => Rule::requiredIf(fn () => !Auth::user()), 'string',
        ];
    }
}
