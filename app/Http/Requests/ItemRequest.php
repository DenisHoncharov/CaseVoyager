<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'type_id' => 'required|exists:types,id',
            'quality' => 'required|numeric',
            'rarity' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'sometimes|string',
        ];
    }
}
