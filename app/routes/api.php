<?php

use App\Http\Controllers\Api\JWTAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::group(['middleware' => 'jwt.auth'], function () {
 
    Route::post('logout', [JWTAuthController::class, 'logout']);
    Route::get('user-info', [JWTAuthController::class,'getUserInfo']);
  
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});