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
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        return $user->can('requestedItem updateStatus');
    }

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
