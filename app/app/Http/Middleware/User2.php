<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use \App\Models\User;

class User2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guard)
    {
        $user = JWTAuth::toUser($request->input('token'));
        $tk =new User();
              if ($tk->getType($user->id)!='user'){
                return response()->json(['error'=>'Không có quyền truy cập']);
              }
        return $next($request);
    }
}
