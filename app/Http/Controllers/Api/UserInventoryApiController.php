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
use Illuminate\Http\Resources\Json\JsonResource;

class UserInventoryApiController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = app()->make('getUserFromDBUsingAuth0');
    }

    /**
     * @OA\Get(
     *     path="/api/user/inventory",
     *     summary="Show user inventory",
     *     tags={"User Inventory"},
     *     @OA\Response(response="200", description="Show user inventory", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ItemsResource"))
     *     )
     *   )
     * )
     *
     * @return JsonResource
     *
     */
    public function index(): JsonResource
    {
        return ItemsResource::collection($this->user->items);
    }

    /**
     * @OA\Post(
     *     path="/api/user/inventory/add",
     *     summary="Add item to user inventory",
     *     tags={"User Inventory"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AddItemToInventoryRequest")
     *     ),
     *     @OA\Response(response="200", description="Add item to user inventory", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ItemsResource"))
     *     )
     *   )
     * )
     *
     * @param AddItemToInventoryRequest $request
     * @return JsonResource
     */
    public function addToInventory(AddItemToInventoryRequest $request): JsonResource
    {
        $itemsFromOpenedCases = collect($request->validated('items'));

        $this->user->items()->attach($itemsFromOpenedCases->pluck('item_id'));

        ReceiveItemFromCaseEvent::dispatch($itemsFromOpenedCases->pluck('openCaseResultId')->toArray());

        return ItemsResource::collection($this->user->items);
    }

    /**
     * @OA\Post(
     *     path="/api/user/inventory/delete",
     *     summary="Delete item from user inventory",
     *     tags={"User Inventory"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RemoveItemFromInventoryRequest")
     *     ),
     *     @OA\Response(response="200", description="Remove item from user inventory", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ItemsResource"))
     *     )
     *   )
     * )
     *
     * @param RemoveItemFromInventoryRequest $request
     * @return JsonResource
     */
    public function removeFromInventory(RemoveItemFromInventoryRequest $request): JsonResource
    {
        $this->user->items()->detach($request->validated('items'));

        return ItemsResource::collection($this->user->items);
    }

    /**
     * @OA\Post(
     *     path="/api/user/inventory/exchange",
     *     summary="Exchange item from user inventory to balance",
     *     tags={"User Inventory"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExchangeInventoryItemToBalanceRequest")
     *     ),
     *     @OA\Response(response="200", description="Exchange item from user inventory to balance", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ItemsResource"))
     *     )
     *   )
     * )
     *
     * @param ExchangeInventoryItemToBalanceRequest $request
     * @return JsonResource
     */
    public function exchangeItems(ExchangeInventoryItemToBalanceRequest $request): JsonResource
    {
        $items = collect($request->validated('items'));

        $itemsCost = Item::whereIn('id', $items)->sum('price');

        $this->user->items()->detach($items);

        UpdateUserBalanceEvent::dispatch($this->user, $itemsCost);

        return ItemsResource::collection($this->user->items);
    }
}
