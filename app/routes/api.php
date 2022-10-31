<?php

use App\Http\Controllers\Api\Admin\ManagerController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\User\UserController;
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

Route::post('/register', [UserController::class,'register'])->name("user.register");
Route::post('/otp', [UserController::class,'authOTP'])->name("user.authotp");
Route::post('/login', [UserController::class,'login'])->name("user.login");

//manager admin
Route::get('/admin/search',[ManagerController::class ,'search'])->name('user.search');
Route::middleware(['jwt.auth','admin'])->group(function(){
    Route::get('/admin',[ManagerController::class ,'index'])->name('user.index');
    Route::get('/admin/{id}',[ManagerController::class ,'show'])->name('user.show');
    Route::post('/admin/create-user',[ManagerController::class ,'store'])->middleware('superadmin')->name('user.store');
    Route::put('/admin/update-user/{id}',[ManagerController::class ,'update'])->middleware('superadmin')->name('user.update');
    Route::delete('/admin/delete/{id}',[ManagerController::class ,'destroy'])->middleware('superadmin')->name('user.destroy');
  
    // Route::apiResource('user',ManagerController::class);
   
});
Route::middleware(['auth:sanctum'])->group(function () {
   
    Route::middleware(['admin'])->group(function () {
        // Route::apiResource('user', ManagerController::class);
        Route::post('/logout', [UserController::class,'logout'])->name("user.logout");
    });
    // Route::post('/dashboard', [DashboardController::class, 'dashboard'])->name("dashboard.dashboard");
    
        
});