<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     title="RequestedItems",
 *     description="RequestedItems model",
 *     @OA\Property (property="id", type="integer", example="1"),
 *     @OA\Property (property="user_id", type="integer", example="1"),
 *     @OA\Property (property="inventory_ids", type="json", example="[1, 2, 3]"),
 *     @OA\Property (property="status", type="string", example="on_approval"),
 *     @OA\Property (property="created_at", type="string", example="2021-08-10T09:00:00.000000Z"),
 *     @OA\Property (property="updated_at", type="string", example="2021-08-10T09:00:00.000000Z"),
 *     @OA\Property (property="deleted_at", type="string", example="2021-08-10T09:00:00.000000Z")
 * )
 */
class RequestedItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const AVAILABLE_STATUSES = [
        'on_approval' => 'on_approval',
        'approved' => 'approved',
        'rejected' => 'rejected',
        'processing' => 'processing',
        'completed' => 'completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
