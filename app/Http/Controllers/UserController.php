<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Reflect the current session cookie information.
     * The method must not expose any sensible information about current user.
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
