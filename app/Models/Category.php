<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $guarded = [];

    public function cases()
    {
        return $this->belongsToMany(Cases::class, 'case_category', 'category_id', 'cases_id');
    }
}
