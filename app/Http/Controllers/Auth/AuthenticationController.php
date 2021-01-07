<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function user()
    {
        $user = auth()->user();
        return User::with('image')->find($user->id);
    }
}