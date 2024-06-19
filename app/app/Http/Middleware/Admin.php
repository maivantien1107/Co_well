<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use \App\Models\User;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // try {
        //     $user = JWTAuth::toUser($request->input('token'));
        //     $tk =new User();
           
        //           if ($tk->getType($user->id)!='admin'){
        //             return response()->json(['error'=>'Không có quyền truy cập']);
        //           }

            
        // }catch (JWTException $e) {
        //     if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        //         return response()->json(['token_expired'], $e->getStatusCode());
        //     }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
        //         return response()->json(['token_invalid'], $e->getStatusCode());
        //     }else{
        //         return response()->json(['error'=>'Token is required']);
        //     }
        // }
        $user = JWTAuth::toUser($request->input('token'));
        $tk =new User();
              if ($tk->getType($user->id)!='admin'&& $tk->getType($user->id)!='superadmin'){
                return response()->json(['error'=>'Không có quyền truy cập']);
              }
        return $next($request);
    }
    
}
