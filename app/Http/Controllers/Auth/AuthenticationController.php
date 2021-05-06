<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\HomeType;
use App\Models\Tags_category;
use App\Models\UserType;
use App\Models\Visibility;

class AuthenticationController extends Controller
{
    public function getauth()
    {

        $app_data = [];
        $app_data['visibilities'] = Visibility::all();
        $app_data['user_types'] = UserType::all();
        $app_data['home_types'] = HomeType::all();
        $app_data['tags_categories'] = Tags_category::all();


        return response()->json([
            'app_data' => $app_data,
            'user' => new AuthResource(auth()->user())
        ], 200);

    }
}