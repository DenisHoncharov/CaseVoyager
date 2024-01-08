<?php

namespace App\Http\Resources;

use App\Models\Item;
use App\Models\UserInventory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'requested_items' => $requestedItems,
            'status' => $this->status,
        ];
    }
}
