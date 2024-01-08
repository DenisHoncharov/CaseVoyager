<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeInventoryItemToBalanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
