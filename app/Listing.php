<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", readOnly=true, example=228, description="Identifier"),
 *     @OA\Property(property="name", type="string", readOnly=true, example="My meetings", description="The listing name"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/User", description="The owner")
 * )
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
