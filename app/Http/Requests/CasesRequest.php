<?php

namespace App\Http\Requests;

use App\Rules\MaximumDropPercentageRule;
use Illuminate\Foundation\Http\FormRequest;

class CasesRequest extends FormRequest
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
            'price' => 'required|numeric',
            'image' => 'sometimes|string',
            'description' => 'sometimes|string',

            'items' => ['array', new MaximumDropPercentageRule],
            'items.*.item_id' => 'exists:items,id',
            'items.*.drop_percentage' => 'numeric|min:0|max:100'
        ];
    }
}
