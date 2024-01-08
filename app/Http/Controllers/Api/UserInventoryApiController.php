<?php

namespace App\Http\Controllers\Api;

use App\Events\ReceiveItemFromCaseEvent;
use App\Events\UpdateUserBalanceEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemToInventoryRequest;
use App\Http\Requests\ExchangeInventoryItemToBalanceRequest;
use App\Http\Requests\RemoveItemFromInventoryRequest;
use App\Http\Resources\ItemsResource;
use App\Models\Item;

class UserInventoryApiController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = app()->make('getUserFromDBUsingAuth0');
    }
    public function index()
    {
        return ItemsResource::collection($this->user->items);
    }

    public function addToInventory(AddItemToInventoryRequest $request)
    {
        $itemsFromOpenedCases = collect($request->validated('items'));

        $this->user->items()->attach($itemsFromOpenedCases->pluck('item_id'));

        ReceiveItemFromCaseEvent::dispatch($itemsFromOpenedCases->pluck('openCaseResultId')->toArray());

        return ItemsResource::collection($this->user->items);
    }

    public function removeFromInventory(RemoveItemFromInventoryRequest $request)
    {
        $this->user->items()->detach($request->validated('items'));

        return ItemsResource::collection($this->user->items);
    }

    public function exchangeItems(ExchangeInventoryItemToBalanceRequest $request)
    {
        $items = collect($request->validated('items'));

        $itemsCost = Item::whereIn('id', $items)->sum('price');

        $this->user->items()->detach($items);

        UpdateUserBalanceEvent::dispatch($this->user, $itemsCost);

        return ItemsResource::collection($this->user->items);
    }
}
