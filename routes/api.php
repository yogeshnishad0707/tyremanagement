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
Route::get('get_req', [UserController::class, 'index']);

Route::post('sendRentLinkEmail', [UserController::class, 'sendRentLinkEmail']);
Route::post('resetPassword', [UserController::class, 'resetPassword']);

// Route::middleware('auth.token')->group(function () {
Route::middleware([TokenAuth::class])->group(function () {
    Route::get('userlist', [UserController::class, 'userlist']);
    Route::post('adduser', [UserController::class, 'adduser']);
    Route::put('updateuser/{id}', [UserController::class, 'updateuser']);
    Route::delete('deleteuser/{id}', [UserController::class, 'deleteuser']);
    Route::get('getroles', [UserController::class, 'getroles']);
    Route::post('multideleteuser', [UserController::class, 'multideleteuser']);
    Route::get('getuserbyRole/{role_id}/{parent_id}', [UserController::class, 'getuserbyRole']);
    Route::get('getparentroles', [UserController::class, 'getparentroles']);
    Route::resource('permissionmaipping', PermissionmaippingController::class);
    Route::get('getpagename', [PermissionmaippingController::class, 'getpagename']);
    Route::get('getcategory', [PermissionmaippingController::class, 'getcategory']);
});

