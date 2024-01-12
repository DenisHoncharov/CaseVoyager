<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="CaseItem",
 *     description="CaseItem model",
 *     @OA\Property (property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="case_id", type="integer", example="1"),
 *     @OA\Property (property="item_id", type="integer", example="1"),
 *     @OA\Property (property="drop_percentage", type="float", example="1.15"),
 *     @OA\Property (property="user_id", description="User who added item to case", type="integer", example="1"),
 *     @OA\Property (property="created_at", type="string", format="date-time", readOnly="true", example="2021-05-05T14:48:01.000000Z"),
 *     @OA\Property (property="updated_at", type="string", format="date-time", readOnly="true", example="2021-05-05T14:48:01.000000Z")
 * )
 *
 */
class CaseItem extends Model
{
    use HasFactory;
    
    protected $table = 'case_item';
}
