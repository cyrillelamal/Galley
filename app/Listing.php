<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($listing_id)
 * @method static Listing findOrFail(int $param)
 * @property User user
 * @property int id
 * @property string name
 */
class Listing extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function getValidationRules(bool $update = false): array
    {
        $rules =  [
            'name' => ['required', 'max:127'],
        ];

        if ($update) {
            foreach ($rules as $field => &$ruleSet) {
                array_unshift($ruleSet, 'sometimes');
            }
        }

        return $rules;
    }

    protected $fillable = [
        'name'
    ];
}
