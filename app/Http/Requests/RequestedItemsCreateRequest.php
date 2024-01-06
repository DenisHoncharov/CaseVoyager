<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestedItemsCreateRequest extends FormRequest
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
            'inventory_ids' => 'required|array',
            'inventory_ids.*' => [
                'required',
                'exists:item_user,id,user_id,' . $user->id,
            ],
        ];
    }
}
