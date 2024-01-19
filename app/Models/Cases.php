<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Cases",
 *     description="Cases model",
 *     @OA\Property (property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property (property="name", type="string", example="Case 1"),
 *     @OA\Property (property="type_id", type="integer", example="1"),
 *     @OA\Property (property="price", type="float", example="1.00"),
 *     @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
 *     @OA\Property (property="description", type="string", example="Description"),
 *     @OA\Property (
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          readOnly="true",
 *          example="2021-08-04T12:00:00.000000Z"
 *      ),
 *     @OA\Property (
 *           property="updated_at",
 *           type="string",
 *           format="date-time",
 *           readOnly="true",
 *           example="2021-08-04T12:00:00.000000Z"
 *       ),
 * )
 */
class Cases extends Model
{
    use HasFactory;

    protected $table = 'cases';
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'case_category', 'cases_id', 'category_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'case_item', 'cases_id', 'item_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
