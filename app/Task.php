<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @property User user
 * @property int id
 * @property string body
 * @property Carbon expires_at
 * @property Listing listing
 */
class Task extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    protected $dates = [
        'expires_at',
    ];

    protected $fillable = [
        'body', 'expires_at',
    ];

    protected $hidden = [
        'user',
    ];
}
