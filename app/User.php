<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1337),
 *     @OA\Property(property="email", type="string", readOnly=true, example="foo@bar.com")
 * )
 * @property Collection tasks
 * @property int id
 * @property Collection listings
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
