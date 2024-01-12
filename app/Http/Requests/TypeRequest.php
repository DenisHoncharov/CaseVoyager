<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="TypeRequest",
 *     description="Type request body data",
 *     required={"name"},
 *     @OA\Property (property="name", type="string", example="type"),
 * )
 */
class TypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:12',
        ];
    }
}
