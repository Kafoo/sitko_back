<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;

class AuthenticationController extends Controller
{
    public function getauth()
    {
        return new AuthResource(auth()->user());
    }
}