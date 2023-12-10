<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
