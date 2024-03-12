<?php

namespace App\Http\Requests;

use App\Rules\MaximumDropPercentageRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="CasesItemsRequest",
 *     description="Cases items request",
 *     required={"items"},
 *     @OA\Property (property="items", type="array", @OA\Items(
 *         @OA\Property (property="item_id", type="integer", example="1"),
 *         @OA\Property (property="drop_percentage", type="integer", example="1"),
 *     )),
 * )
 */
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
            'items' => ['array', new MaximumDropPercentageRule()],
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.drop_percentage' => 'required|numeric|min:0|max:100|required_with:items.*.item_id',
        ];
    }
}
