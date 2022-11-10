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


Route::post('/login', [AdminController::class,'login'])->name('admin.login');
Route::post('/otp', [AdminController::class,'authOTP'])->name('admin.authotp');

//user
Route::post('/customer-register', [UserController::class,'register'])->name("user.register");
Route::post('/user-active-account', [UserController::class,'activeAccount'])->name("user.activeAccount");
Route::post('/customer-login', [UserController::class,'login'])->name("user.login");
Route::post('/forget-password', [UserController::class,'forgetPassword'])->name('user.forgetPassword');
Route::post('/new-password', [UserController::class,'newPassword'])->name('user.newPassword');
Route::post('/verify-phone', [UserController::class,'verifiedPhone'])->name('user.verifiedPhone');
Route::get('/active-email/{token}', [UserController::class,'customerActiveMail'])->name('user.active-email');

//manager admin
Route::get('/admin/export/{data}', [ManagerController::class, 'export']);
Route::get('/admin/search',[ManagerController::class ,'search'])->name('user.search');
Route::get('admin/sendMail',[AdminController::class, 'mailsend'])->name('user.sendmail');
Route::get('/admin',[ManagerController::class ,'index'])->name('user.index');
Route::get('/admin/getAll',[ManagerController::class ,'getAll'])->name('user.getAll');
Route::get('/admin/{id}',[ManagerController::class ,'show'])->name('user.show');
Route::post('/admin/create-user',[ManagerController::class ,'store']);
Route::put('/admin/update-user/{id}',[ManagerController::class ,'update']);
Route::delete('/admin/delete/{id}',[ManagerController::class ,'destroy']);
Route::middleware(['jwt.auth','admin'])->group(function(){

    // Route::get('/admin',[ManagerController::class ,'index'])->name('user.index');
    // Route::get('/admin/{id}',[ManagerController::class ,'show'])->name('user.show');
    // Route::post('/admin/create-user',[ManagerController::class ,'store'])->middleware('superadmin')->name('user.store');
    // Route::put('/admin/update-user/{id}',[ManagerController::class ,'update'])->middleware('superadmin')->name('user.update');
    // Route::delete('/admin/delete/{id}',[ManagerController::class ,'destroy'])->middleware('superadmin')->name('user.destroy');
  
    // Route::apiResource('user',ManagerController::class);
   
});
Route::middleware(['auth:sanctum'])->group(function () {
   
    Route::middleware(['admin'])->group(function () {
        // Route::apiResource('user', ManagerController::class);
        Route::post('/logout', [UserController::class,'logout'])->name("user.logout");
    });
    // Route::post('/dashboard', [DashboardController::class, 'dashboard'])->name("dashboard.dashboard");
    
        
});