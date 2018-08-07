<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Authorizations\AuthorizationService;
use Validator;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthorizationController extends Controller
{

    protected $AuthorizationController;
    protected $auth;

    /**
     * Dependency injection of AuthorizationService class,
     *
     * @param $auth
     */
    public function  __construct(AuthorizationService $auth){
        $this->auth = $auth;
    }



    /**
     * Register a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function register(Request $req)
    {
        try{
            $data = $this->auth->registerUser($req);
            return response()->json($data,201);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    /**
     * Logout a user(delete his token)
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $req)
    {
        try{
            $this->auth->logoutUser($req);
            return response()->json(['message' => 'You successfully logged out']
                ,200);
        }catch (ModelNotFoundException $ex){
            throw $ex;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
        }
        
    }

    /**
     * Login the user (generate token)
     *
     * @param  \Illuminate\Http\Request   $req
     * @return \Illuminate\Http\Response
     */

    public function login(Request $req)
    {
        $user = User::where('email',$req->email)->first();
        if(!$user) {
            return response()->json(['status'=>'error','message' => 'User not found'],404);
        }
        if(Hash::check($req->password, $user->password)) {
            $user->update(['api_token'=>str_random(60)]);
            return response()->json(['status'=>'success', 'user' => $user],200);
        }
        return response()->json(['status'=>'error', 'message' => 'Invalid Credentials'], 401);

    }

}
