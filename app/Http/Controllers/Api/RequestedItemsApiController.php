<?php

namespace App\Http\Controllers\Api;

use App\Events\RequestItemEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestedItemsCreateRequest;
use App\Http\Requests\RequestedItemsUpdateStatusRequest;
use App\Http\Resources\RequestedItemsResource;
use App\Models\RequestedItems;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RequestedItemsApiController extends Controller
{
    public function index(Request $request)
    {
        $this->user = app()->make('getUserFromDBUsingAuth0');

        if ($request->has('isAdmin') && $this->user->isAdmin()) {
            $requestedItems = RequestedItems::all();
        } else {
            $requestedItems = $this->user->requestedItems()->get();
        }

        return RequestedItemsResource::collection($requestedItems);
    }

    public function create(RequestedItemsCreateRequest $request)
    {
        $this->user = app()->make('getUserFromDBUsingAuth0');

        $userInventoryIds = $request->validated('inventory_ids');

        $this->user->requestedItems()->create([
            'inventory_ids' => json_encode($userInventoryIds),
            'status' => 'on_approval',
        ]);

        RequestItemEvent::dispatch($userInventoryIds, true);

        return response()->json([
            'message' => 'Items requested successfully',
        ]);
    }

    public function update(RequestedItemsUpdateStatusRequest $request, RequestedItems $requestedItem)
    {
        $requestedItem->update([
            'status' => $request->validated('status'),
        ]);

        return response()->json([
            'message' => 'Requested item status updated successfully',
        ]);
    }

    public function delete(RequestedItems $requestedItem)
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
