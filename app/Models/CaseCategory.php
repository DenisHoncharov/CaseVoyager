<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="CaseCategory",
 *     description="CaseCategory model",
 *     @OA\Property (property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="case_id", type="integer", example="1"),
 *     @OA\Property (property="category_id", type="integer", example="1"),
 *     @OA\Property (property="user_id", type="integer", example="1"),
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
class CaseCategory extends Model
{
    use HasFactory;

    protected $table = 'case_category';
}
