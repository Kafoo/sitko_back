<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        //First Name will be considered as Alias if no Last Name is provided.
        //If so, Alias has to be unique 
        if ($data['last_name']) {
            $nameRules = ['required', 'string', 'max:255'];
        }else{ 
            $nameRules = ['required', 'string', 'max:255', 'unique:users'];
        }

        return Validator::make($data, [
            'name' => $nameRules,
            'last_name' => ['max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'name.unique' => trans('validation.custom.unique.name', ['input' => $data['name']]),
            'email.unique' => trans('validation.custom.unique.email', ['input' => $data['email']])
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        if ($data['last_name']) {
            $last_name = $data['last_name'];
        }else{
            $last_name = null;
        }

        return User::create([
            'name' => $data['name'],
            'last_name' => $last_name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return response()->json([
            'token'    => $user->createToken($request->input('device_name'))->accessToken,
            'user'     => $request->user()
        ]);
    }
}
