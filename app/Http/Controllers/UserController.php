<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * Reflect the current session cookie information.
     * The method must not expose any sensible information about current user.
     * @OA\Get(
     *     path="/api/reflect_user",
     *     @OA\Response(
     *     response="200",
     *     description="The information about the current user.",
     *     @OA\JsonContent(
     *     @OA\Property(
     *     property="user", type="object",
     *     ref="#/components/schemas/User"
     * )
     * )
     * )
     * )
     *
     * @return array
     */
    public function reflectUser()
    {
        return [
            'user' => Auth::user()
        ];
    }
}
