<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PermissionmaippingController;
use App\Http\Middleware\TokenAuth;
use App\Http\Controllers\MtyretypeController;
use App\Http\Controllers\MtypesizeController;
use App\Http\Controllers\MtruckmakeController;
use App\Http\Controllers\MtruckmodelController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
// Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware(TokenAuth::class);
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
    // Route::get('GetUserbyRoleId/{role_id}/{parent_id}', [UserController::class, 'GetUserbyRoleId']);
    Route::get('getUserByRoleId', [UserController::class, 'getUserByRoleId']);
    Route::get('getparentroles', [UserController::class, 'getparentroles']);
    Route::resource('permissionmaipping', PermissionmaippingController::class);
    Route::get('getpagename', [PermissionmaippingController::class, 'getpagename']);
    Route::get('getcategory', [PermissionmaippingController::class, 'getcategory']);
    Route::resource('mtyretype',MtyretypeController::class);
    Route::put('updatetyretype/{id}', [MtyretypeController::class, 'updatetyretype']);
    Route::delete('deletetyretype/{id}', [MtyretypeController::class, 'deletetyretype']);
    Route::get('tyresizelist', [MtypesizeController::class, 'tyresizelist']);
    Route::post('inserttyresize', [MtypesizeController::class, 'inserttyresize']);
    Route::put('updatetyresize/{id}', [MtypesizeController::class, 'updatetyresize']);
    Route::delete('deletetyresize/{id}', [MtypesizeController::class, 'deletetyresize']);
    Route::get('gettyretype', [MtypesizeController::class, 'gettyretype']);
    Route::get('truckmakelist', [MtruckmakeController::class, 'truckmakelist']);
    Route::post('inserttruckmake', [MtruckmakeController::class, 'inserttruckmake']);
    Route::put('updatetruckmake/{id}', [MtruckmakeController::class, 'updatetruckmake']);
    Route::delete('deletetruckmake/{id}', [MtruckmakeController::class, 'deletetruckmake']);

    Route::get('truckmodellist', [MtruckmodelController::class, 'truckmodellist']);
    Route::post('insertruckmodel', [MtruckmodelController::class, 'insertruckmodel']);
    Route::put('updatetruckmodel/{id}', [MtruckmodelController::class, 'updatetruckmodel']);
    Route::delete('deletetruckmodel/{id}', [MtruckmodelController::class, 'deletetruckmodel']);
    Route::get('gettruckmake', [MtruckmodelController::class, 'gettruckmake']);
});
