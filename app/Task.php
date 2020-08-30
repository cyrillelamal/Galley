<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Task|null find($id)
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

    public static function getValidationRules(bool $update = false): array
    {
        $rules = [
            'body' => ['required', 'max:511'],
            'expires_at' => ['nullable', 'date'],
//            'listing_id' => ['sometimes', 'exists:listings,id']
        ];

        if ($update) {
            foreach ($rules as $field => &$fieldRules) {
                array_unshift($fieldRules, 'sometimes');
            }
        }

        return $rules;
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
