<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function getauth()
    {

        $user = auth()->user();
        $user->place = auth()->user()->place;

        return $user;
    }
}