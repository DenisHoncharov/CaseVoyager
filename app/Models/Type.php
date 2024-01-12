<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Type",
 *     description="Type model",
 *     @OA\Property (property="id", type="integer", example="1"),
 *     @OA\Property (property="name", type="string", example="CS2"),
 *     @OA\Property (property="created_at", type="string", format="date-time", example="2021-08-04T12:00:00.000000Z"),
 *     @OA\Property (property="updated_at", type="string", format="date-time", example="2021-08-04T12:00:00.000000Z")
 * )
 */
class Type extends Model
{
    use HasFactory;

    protected $table = 'types';
    protected $guarded = [];

    public function cases()
    {
        return $this->hasMany(Cases::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
