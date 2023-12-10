<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CasesItemsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.drop_percentage' => 'required|numeric|min:0|max:100'
        ];
    }
}
