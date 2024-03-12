<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="ItemRequest",
 *     description="Item request body data",
 *     required={"name", "type_id", "quality", "rarity", "price"},
 *     @OA\Property (property="name", type="string", example="Item 1"),
 *     @OA\Property (property="type_id", type="integer", example="1"),
 *     @OA\Property (property="quality", type="integer", example="1"),
 *     @OA\Property (property="rarity", type="string", example="Rarity 1"),
 *     @OA\Property (property="price", type="float", example="1.00"),
 *     @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
 * )
 */
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
