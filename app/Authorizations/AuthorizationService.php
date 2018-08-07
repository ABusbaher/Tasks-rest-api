<?php

namespace App\Authorizations;

use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthorizationService 
{

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function registerUser(Request $req)
    {
        $user = $req->validate([
            "name"                => 'required',
            "email"               => 'required|unique:users|email',
            "password"            => 'required|min:5'
        ]);

        $user = new User();
        $user->name         = $req->input('name');
        $user->email        = $req->input('email');
        $user->password     = bcrypt($req->input('password'));
        $user->api_token    = str_random(60);
        $user->save();
        $rez = response()->json(['user' => $user]);
        return $rez;
    }

    /**
     * Logout a user(delete his token)
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function logoutUser(Request $req)
    {
        $api_token = $req->input('api_token');
        $user = User::where('api_token',$api_token)->firstOrFail();
        $user->update(['api_token'=>null]);
    }
}
