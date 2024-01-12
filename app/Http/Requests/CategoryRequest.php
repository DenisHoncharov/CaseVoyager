<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="CategoryRequest",
 *     description="Category request body data",
 *     required={"name", "type_id"},
 *     @OA\Property (property="name", type="string", example="Category 1"),
 *     @OA\Property (property="type_id", type="integer", example="1"),
 *     @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
 *     @OA\Property (property="cases", type="array", @OA\Items(type="integer", example="1")),
 * )
 */
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
            'name' => 'required|string|max:30',
            'type_id' => 'required|exists:types,id',
            'image' => 'sometimes|string',

            'cases' => 'array',
            'cases.*' => 'numeric|exists:cases,id'
        ];
    }
}
