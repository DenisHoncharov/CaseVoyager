<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="ExchangeInventoryItemToBalanceRequest",
 *     description="ExchangeInventoryItemToBalance request body data",
 *     required={"items"},
 *     @OA\Property (property="items", type="array", @OA\Items(type="integer", example="1")),
 * )
 */
class ExchangeInventoryItemToBalanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     * @throws BindingResolutionException
     * @throws BindingResolutionException
     */
    public function rules(): array
    {
        $user = app()->make('getUserFromDBUsingAuth0');
        return [
            'items' => 'required|array',
            'items.*' => 'required|exists:item_user,item_id,user_id,' . $user->id,
        ];
    }
}
