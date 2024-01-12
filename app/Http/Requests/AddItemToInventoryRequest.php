<?php

namespace App\Http\Requests;

use App\Rules\IsItemFromUserOpenedCaseRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="AddItemToInventoryRequest",
 *     description="Add item to inventory request body data",
 *     required={"items"},
 *     @OA\Property (property="items", type="array", @OA\Items(
 *         @OA\Property (property="openCaseResultId", type="integer", example="1"),
 *         @OA\Property (property="item_id", type="integer", example="1"),
 *     )),
 * )
 */
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
