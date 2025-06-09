<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|file|mimes:jpeg,png',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'condition' => 'required|string',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'required|array|min:1',
        ];
    }


    public function messages(): array
    {
        return [
            'category_ids.required' => 'カテゴリを1つ以上選択してください。',
        ];
    }
}
