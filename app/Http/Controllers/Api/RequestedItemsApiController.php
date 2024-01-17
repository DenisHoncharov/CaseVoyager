<?php

namespace App\Http\Controllers\Api;

use App\Events\RequestItemEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestedItemsCreateRequest;
use App\Http\Requests\RequestedItemsUpdateStatusRequest;
use App\Http\Resources\RequestedItemsResource;
use App\Models\RequestedItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class RequestedItemsApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/requestedItems",
     *     summary="Show all requested items",
     *     tags={"Requested Items"},
     *     @OA\Response(response="200", description="Show all requested items", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/RequestedItemsResource"))
     *     )
     *   )
     * )
     *
     * @param Request $request
     * @return JsonResource
     *
     */
    public function index(Request $request): JsonResource
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        if ($request->has('isAdmin') && $user->can('requestedItem viewAllRequests')) {
            $requestedItems = RequestedItems::all();
        } else {
            $requestedItems = $user->requestedItems()->get();
        }

        return RequestedItemsResource::collection($requestedItems);
    }

    /**
     * @OA\Post(
     *     path="/api/requestedItems/create",
     *     summary="Create requested items",
     *     tags={"Requested Items"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RequestedItemsCreateRequest")
     *     ),
     *     @OA\Response(response="200", description="Create requested items", @OA\JsonContent(
     *       ref="#/components/schemas/RequestedItems"
     *     )
     *   )
     * )
     *
     * @param RequestedItemsCreateRequest $request
     * @return JsonResponse
     */
    public function create(RequestedItemsCreateRequest $request): JsonResponse
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        $userInventoryIds = $request->validated('inventory_ids');

        $user->requestedItems()->create([
            'inventory_ids' => json_encode($userInventoryIds),
            'status' => 'on_approval',
        ]);

        RequestItemEvent::dispatch($userInventoryIds, true);

        return response()->json([
            'message' => 'Items requested successfully',
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/requestedItems/{requestedItem}",
     *     summary="Update requested item status",
     *     tags={"Requested Items"},
     *     @OA\Parameter(
     *         name="requestedItem",
     *         in="path",
     *         description="Requested item id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RequestedItemsUpdateStatusRequest")
     *     ),
     *     @OA\Response(response="200", description="Update requested item status", @OA\JsonContent(
     *       ref="#/components/schemas/RequestedItems"
     *     )
     *   )
     * )
     *
     * @param RequestedItemsUpdateStatusRequest $request
     * @param RequestedItems $requestedItem
     * @return JsonResponse
     */
    public function update(RequestedItemsUpdateStatusRequest $request, RequestedItems $requestedItem): JsonResponse
    {
        $requestedItem->update([
            'status' => $request->validated('status'),
        ]);

        return response()->json([
            'message' => 'Requested item status updated successfully',
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/requestedItems/{requestedItem}",
     *     summary="Delete requested item",
     *     tags={"Requested Items"},
     *     @OA\Parameter(
     *         name="requestedItem",
     *         in="path",
     *         description="Requested item id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Delete requested item", @OA\JsonContent(
     *       ref="#/components/schemas/RequestedItems"
     *     )
     *   )
     * )
     *
     * @param RequestedItems $requestedItem
     * @return JsonResponse|ValidationException
     */
    public function delete(RequestedItems $requestedItem): JsonResponse|ValidationException
    {
        if ($requestedItem->status === 'on_approval') {
            $isDeleted = $requestedItem->delete();

            if (!$isDeleted) {
                throw ValidationException::withMessages(['status' => 'Requested item was not deleted']);
            }

            RequestItemEvent::dispatch(json_decode($requestedItem->inventory_ids), false);

            return response()->json([
                'message' => 'Requested item deleted successfully',
            ]);
        } else {
            throw ValidationException::withMessages(['status' => 'Only items with status "on_approval" can be deleted']);
        }
    }
}
