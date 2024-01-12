<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Property (property="id", type="integer", example="1"),
 *     @OA\Property (property="auth0_id", type="string", example="google-oauth2|12345678901234"),
 *     @OA\Property (property="telegram_id", type="string", example="1234567890"),
 *     @OA\Property (property="steam_profile_url", type="string", example="https://steamcommunity.com/profiles/1234567890"),
 *     @OA\Property (property="steam_trade_link", type="string", example="https://steamcommunity.com/tradeoffer/new/?partner=1234567890&token=1234567890"),
 *     @OA\Property (property="balance", type="float", example="1.15"),
 *     @OA\Property (property="email", type="string", example="example@email.com"),
 *     @OA\Property (property="email_verified_at", type="string", format="date-time", example="2021-08-04T12:00:00.000000Z"),
 *     @OA\Property (property="created_at", type="string", format="date-time", example="2021-08-04T12:00:00.000000Z"),
 *     @OA\Property (property="updated_at", type="string", format="date-time", example="2021-08-04T12:00:00.000000Z")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auth0_id',
        'balance',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function requestedItems()
    {
        return $this->hasMany(RequestedItems::class);
    }

    //TODO: Implement isAdmin
    public function isAdmin() :bool
    {
        //TODO: Implement isAdmin logic
        return true;
    }
}
