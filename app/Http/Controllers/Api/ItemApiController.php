<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemsResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/items",
     *     summary="Show all items",
     *     tags={"Items"},
     *     @OA\Response(response="200", description="Show all items", @OA\JsonContent(
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
        return ItemsResource::collection(Item::all());
    }

    /**
     * @OA\Get(
     *     path="/api/items/{item}",
     *     summary="Show item",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Show item", @OA\JsonContent(
     *         ref="#/components/schemas/Item"
     *     )
     *   )
     * )
     *
     * @param Item $item
     * @return JsonResponse
     */
    public function show(Item $item): JsonResponse
    {
        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'type' => $item->type,
            'image' => $item->image,
            'price' => $item->price,
            'quality' => $item->quality,
            'rarity' => $item->rarity,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/items/create",
     *     summary="Create item",
     *     tags={"Items"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ItemRequest")
     *     ),
     *     @OA\Response(response="200", description="Create item", @OA\JsonContent(
     *         ref="#/components/schemas/Item"
     *     )
     *   )
     * )
     *
     * @param ItemRequest $request
     * @return JsonResponse
     */
    public function create(ItemRequest $request): JsonResponse
    {
        $item = Item::create($request->validated());

        return response()->json($item);
    }

    /**
     * @OA\Put(
     *     path="/api/items/{item}",
     *     summary="Update item",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ItemRequest")
     *     ),
     *     @OA\Response(response="200", description="Update item", @OA\JsonContent(
     *         ref="#/components/schemas/Item"
     *     )
     *   )
     * )
     *
     * @param ItemRequest $request
     * @param Item $item
     * @return JsonResponse
     */
    public function update(ItemRequest $request, Item $item): JsonResponse
    {
        $item = $item->update($request->validated());

        return response()->json($item);
    }

    /**
     * @OA\Delete(
     *     path="/api/items/{item}",
     *     summary="Delete item",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Delete item")
     * )
     *
     * @param Item $item
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Item $item): JsonResponse
    {
        $item->delete();

        return response()->json();
    }
}
