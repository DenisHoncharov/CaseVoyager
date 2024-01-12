<?php

namespace App\Http\Requests;

use App\Rules\MaximumDropPercentageRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="CasesRequest",
 *     description="Cases request body data",
 *     required={"name", "type_id", "price"},
 *     @OA\Property (property="name", type="string", example="Case 1"),
 *     @OA\Property (property="type_id", type="integer", example="1"),
 *     @OA\Property (property="price", type="float", example="1.00"),
 *     @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
 *     @OA\Property (property="description", type="string", example="Description"),
 *     @OA\Property (property="items", type="array", @OA\Items(
 *         @OA\Property (property="item_id", type="integer", example="1"),
 *         @OA\Property (property="drop_percentage", type="float", example="1.00"),
 *     )),
 * )
 */
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
