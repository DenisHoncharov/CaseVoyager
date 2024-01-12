<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="RemoveItemFromInventoryRequest",
 *     description="RemoveItemFromInventory request body data",
 *     required={"items"},
 *     @OA\Property (property="items", type="array", @OA\Items(type="integer", example="1")),
 * )
 */
class RemoveItemFromInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:items,id',
        ];
    }
}
