<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zip_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string|max:255',
            'building' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'zip_code.required' => '郵便番号は入力必須です。',
            'zip_code.regex' => '郵便番号はハイフンありの8文字で入力してください。',
            'address.required' => '住所は入力必須です。',
            'building.required' => '建物名は入力必須です。',
        ];
    }
}
