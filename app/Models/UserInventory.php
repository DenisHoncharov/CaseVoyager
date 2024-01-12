<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="UserInventory",
 *     description="UserInventory model",
 *     @OA\Property (property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="user_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="item_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="is_requested", type="boolean", readOnly="true", example="false"),
 *     @OA\Property (property="created_at", type="string", readOnly="true", example="2021-06-01T00:00:00.000000Z"),
 *     @OA\Property (property="updated_at", type="string", readOnly="true", example="2021-06-01T00:00:00.000000Z")
 * )
 */
class UserInventory extends Model
{
    use HasFactory;

    protected $table = 'item_user';

    protected $fillable = [
        'is_requested',
    ];
}
