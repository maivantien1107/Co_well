<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
 

class JWTAuthController extends Controller
{
   

    public $token = true;
  
    public function register(Request $request)
    {
 
         $validator = Validator::make($request->all(), 
                      [ 
                      'name' => 'required',
                      'email' => 'required|email',
                      'password' => 'required',  
                      'c_password' => 'required|same:password', 
                     ]);  
 
         if ($validator->fails()) {  
 
               return response()->json(['error'=>$validator->errors()], 401); 
 
            }   
 
 
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
  
        if ($this->token) {
            return $this->login($request);
        }
  
        return response()->json([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
    }
  
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        $token_type='bearer';
        return response()->json(compact('token','token_type'));
    }

    public function getUserInfo(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }
  
    public function logout(Request $request)
    {

        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);

      
    }
    
}
