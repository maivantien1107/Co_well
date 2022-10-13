<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\User\UserController;
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


Route::post('/login', [AdminController::class,'login'])->name('admin.login');
Route::post('/reset-password', [AdminController::class, 'sendMail'])->name('admin.sendMailResetPassword');
Route::put('/reset-password/{token}', [AdminController::class, 'resetPassword'])->name('admin.resetPassword');

//user
Route::post('/customer-register', [UserController::class,'register'])->name("user.register");
Route::post('/user-active-account', [UserController::class,'activeAccount'])->name("user.activeAccount");
Route::post('/customer-login', [UserController::class,'login'])->name("user.login");
Route::post('/forget-password', [UserController::class,'forgetPassword'])->name('user.forgetPassword');
Route::post('/new-password', [UserController::class,'newPassword'])->name('user.newPassword');
Route::post('/verify-phone', [UserController::class,'verifiedPhone'])->name('user.verifiedPhone');
Route::get('/active-email/{token}', [UserController::class,'customerActiveMail'])->name('user.active-email');