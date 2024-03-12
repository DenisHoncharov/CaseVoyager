<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="ExchangeOpenedItemsRequest",
 *     description="ExchangeOpenedItems request body data",
 *     required={"openedCasesIds"},
 *     @OA\Property (property="openedCasesIds", type="array", @OA\Items(type="integer", example="1")),
 * )
 */
class ExchangeOpenedItemsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     * @throws BindingResolutionException
     */
    public function rules(): array
    {
        $user = app()->make('getUserFromDBUsingAuth0');
        return [
            'openedCasesIds' => 'required|array',
            'openedCasesIds.*' => 'required|distinct|exists:open_case_results,id,user_id,' . $user->id,
        ];
    }
}
