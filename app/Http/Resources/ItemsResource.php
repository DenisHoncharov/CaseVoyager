<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ItemsResource",
 *     description="Items resource",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="name", type="string", example="Item name"),
 *     @OA\Property(property="image", type="string", example="https://i.imgur.com/1.jpg"),
 *     @OA\Property(property="type", type="string", example="CS2"),
 *     @OA\Property(property="price", type="float", example="100.02"),
 *     @OA\Property(property="quality", type="float", example="0.02"),
 *     @OA\Property(property="rarity", type="string", example="rare"),
 *     @OA\Property(property="source_marketplace_link", type="string", example="https://google.com"),
 *     @OA\Property(property="source_preview_link", type="string", example="https://google.com")
 * )
 */
class ItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'type' => $this->type,
            'price' => $this->price,
            'quality' => $this->quality,
            'rarity' => $this->rarity,
            'source_marketplace_link' => $this->source_marketplace_link,
            'source_preview_link' => $this->source_preview_link,
        ];
    }
}
