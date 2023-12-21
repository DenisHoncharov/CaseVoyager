<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
