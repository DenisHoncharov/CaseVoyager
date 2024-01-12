<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Category",
 *     description="Category model",
 *     @OA\Property (property="id", type="integer", example="1"),
 *     @OA\Property (property="name", type="string", example="Category name"),
 *     @OA\Property (property="type_id", type="integer", example="1"),
 *     @OA\Property (property="image", type="string", example="https://i.imgur.com/1.jpg"),
 *     @OA\Property (property="created_at", type="string", format="date-time", example="2021-08-25 12:00:00"),
 *     @OA\Property (property="updated_at", type="string", format="date-time", example="2021-08-25 12:00:00")
 * )
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $guarded = [];

    public function cases()
    {
        return $this->belongsToMany(Cases::class, 'case_category', 'category_id', 'cases_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
