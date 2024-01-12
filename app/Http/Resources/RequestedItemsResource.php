<?php

namespace App\Http\Resources;

use App\Models\Item;
use App\Models\UserInventory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="RequestedItemsResource",
 *     description="Requested items resource",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="requested_items", type="array", @OA\Items(ref="#/components/schemas/ItemsResource")),
 *     @OA\Property(property="status", type="string", example="pending"),
 * )
 */
class RequestedItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $requestedItems = Item::whereIn('id', UserInventory::whereIn('id', json_decode($this->inventory_ids))->pluck('item_id'))
            ->get();

        return [
            'id' => $this->id,
            'requested_items' => ItemsResource::collection($requestedItems),
            'status' => $this->status,
        ];
    }
}
