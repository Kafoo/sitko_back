<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

	public function index(Request $request)
	{
		return User::all();
	}


	public function destroy($userID)
	{
		return User::find($userID)->delete();
	}
}
