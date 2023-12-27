<?php

namespace App\Http\Controllers\Api;

use App\Events\ReceiveItemFromCaseEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemToInventoryRequest;
use App\Http\Requests\RemoveItemFromInventoryRequest;
use App\Http\Resources\ItemsResource;

class UserInventoryApiController extends Controller
{
    public function index()
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        return ItemsResource::collection($user->items);
    }

    public function addToInventory(AddItemToInventoryRequest $request)
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        $itemsFromOpenedCases = collect($request->validated('items'));

        $user->items()->attach($itemsFromOpenedCases->pluck('item_id'));

        ReceiveItemFromCaseEvent::dispatch($itemsFromOpenedCases->pluck('openCaseResultId')->toArray());

        return ItemsResource::collection($user->items);
    }

    public function removeFromInventory(RemoveItemFromInventoryRequest $request)
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        $user->items()->detach($request->validated('items'));

        return ItemsResource::collection($user->items);
    }
}
