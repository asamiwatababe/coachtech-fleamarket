<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'ユーザー名は必須です。',
            'username.string' => 'ユーザー名は文字列で入力してください。',
            'username.max' => 'ユーザー名は255文字以内で入力してください。',

            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.string' => '郵便番号は文字列で入力してください。',
            'postal_code.max' => '郵便番号は10文字以内で入力してください。',

            'address.required' => '住所は必須です。',
            'address.string' => '住所は文字列で入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',

            'building.string' => '建物名は文字列で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',

            'image.file' => '画像はファイル形式で選択してください。',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください。',
        ];
    }
}
