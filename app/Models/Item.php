<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Item",
 *     description="Item model",
 *     @OA\Property (property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="name", type="string", example="AK-47 | Redline"),
 *     @OA\Property (property="type_id", type="integer", example="1"),
 *     @OA\Property (property="price", type="float", example="1.00"),
 *     @OA\Property (property="quality", type="float", example="1.15"),
 *     @OA\Property (property="rarity", type="string", example="Covert"),
 *     @OA\Property (property="image", type="string", example="https://cdn.csgo.com/item/AK-47/300.png"),
 *     @OA\Property (property="source_marketplace_link", type="string", example="https://steamcommunity.com/market"),
 *     @OA\Property (property="source_preview_link", type="string", example="https://csgo.steamanalyst.com/id/1"),
 *     @OA\Property (
 *           property="created_at",
 *           type="string",
 *           format="date-time",
 *           readOnly="true",
 *           example="2021-08-04T12:00:00.000000Z"
 *       ),
 *      @OA\Property (
 *            property="updated_at",
 *            type="string",
 *            format="date-time",
 *            readOnly="true",
 *            example="2021-08-04T12:00:00.000000Z"
 *        ),
 * )
 */
class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $guarded = [];

    public function cases()
    {
        return $this->belongsToMany(
            Cases::class,
            'case_item',
            'item_id',
            'cases_id'
        );
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
