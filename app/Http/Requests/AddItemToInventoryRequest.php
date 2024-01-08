<?php

namespace App\Http\Requests;

use App\Rules\IsItemFromUserOpenedCaseRule;
use Illuminate\Foundation\Http\FormRequest;

class AddItemToInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', new IsItemFromUserOpenedCaseRule],
            'items.*.openCaseResultId' => 'required|integer|distinct|exists:open_case_results,id',
            'items.*.item_id' => 'required|integer|exists:items,id',
        ];
    }
}
