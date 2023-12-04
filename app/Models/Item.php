<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    public function categories()
    {
        //TODO: update this relations (not used)
        return $this->belongsToMany(Category::class);
    }

    public function cases()
    {
        return $this->belongsToMany(Cases::class);
    }
}
