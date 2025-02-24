<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PermissionmaippingController;
use App\Http\Middleware\TokenAuth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware(TokenAuth::class);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])


// Route::middleware('auth.token')->group(function () {
// Route::middleware([TokenAuth::class])->group(function () {
    Route::get('userlist', [UserController::class, 'userlist']);
    Route::post('adduser', [UserController::class, 'adduser']);
    Route::post('updateuser/{userid}', [UserController::class, 'updateuser']);
    Route::post('deleteuser/{id}', [UserController::class, 'deleteuser']);
    Route::post('multideleteuser', [UserController::class, 'multideleteuser']);
    Route::get('getroles', [UserController::class, 'getroles']);
    Route::get('getparentroles', [UserController::class, 'getparentroles']);
    Route::resource('permissionmaipping', PermissionmaippingController::class);
    Route::get('getpagename', [PermissionmaippingController::class, 'getpagename']);
// });
