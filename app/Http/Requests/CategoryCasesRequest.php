<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="CasesItemsRequest",
 *      description="Cases items request body data",
 *      required={"cases"},
 *     @OA\Property (property="cases", type="array", @OA\Items(type="integer", example="1")),
 * )
 */
class CategoryCasesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cases' => 'array',
            'cases.*' => 'exists:cases,id'
        ];
    }
}
