<?php

namespace App\Http\Requests;

use App\Models\RequestedItems;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     title="RequestedItemsUpdateStatusRequest",
 *     description="RequestedItemsUpdateStatus request body data",
 *     required={"status"},
 *     @OA\Property (property="status", type="string", example="pending"),
 * )
 */
class RequestedItemsUpdateStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:' . Rule::in(RequestedItems::AVAILABLE_STATUSES)],
        ];
    }
}
