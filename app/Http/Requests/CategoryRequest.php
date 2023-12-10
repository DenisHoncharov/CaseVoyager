<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:12',
            'type' => 'required|string|in:CS2',
            'image' => 'sometimes|string',

            'cases' => 'array',
            'cases.*' => 'exists:cases,id'
        ];
    }
}
