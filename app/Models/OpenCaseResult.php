<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="OpenCaseResult",
 *     description="OpenCaseResult model",
 *     @OA\Property (property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="user_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="opened_case_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="item_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="is_received", type="boolean", readOnly="true", example="1"),
 *     @OA\Property (property="created_at", type="string", readOnly="true", example="2021-08-25T12:00:00.000000Z"),
 *     @OA\Property (property="updated_at", type="string", readOnly="true", example="2021-08-25T12:00:00.000000Z"),
 * )
 */
class OpenCaseResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function userOpenedCase()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function openedCase()
    {
        return $this->belongsTo(Cases::class, 'opened_case_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
