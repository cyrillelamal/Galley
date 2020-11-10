<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", readOnly=true, example=228, description="Identifier"),
 *     @OA\Property(property="body", type="string", readOnly=true, example="Foo bar", description="Content"),
 *     @OA\Property(property="expires_at", type="string", format="date-time", readOnly=true, example="2020-11-10T14:25:16+00:00", description="Content"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 *     @OA\Property(property="listings", type="array", ref="#/components/schemas/User",
 *          @OA\Items(type="object", ref="#/components/schemas/Listing")
 *     )
 * )
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
