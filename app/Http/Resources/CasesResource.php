<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="CasesResource",
 *     description="Cases resource",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="name", type="string", example="Case name"),
 *     @OA\Property(property="type", type="string", example="CS2"),
 *     @OA\Property(property="price", type="float", example="100.02"),
 *     @OA\Property(property="image", type="string", example="https://i.imgur.com/1.jpg"),
 *     @OA\Property(property="description", type="string", example="Case description"),
 * )
 */
class CasesResource extends JsonResource
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
            'type' => $this->type,
            'price' => $this->price,
            'image' => $this->image,
            'description' => $this->description
        ];
    }
}
