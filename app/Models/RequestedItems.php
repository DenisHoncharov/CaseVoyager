<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestedItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const AVAILABLE_STATUSES = [
        'on_approval' => 'on_approval',
        'approved' => 'approved',
        'rejected' => 'rejected',
        'processing' => 'processing',
        'completed' => 'completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
