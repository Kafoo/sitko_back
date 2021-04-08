<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class AuthenticationController extends Controller
{
    public function getauth()
    {
        return new UserResource(auth()->user());
    }
}