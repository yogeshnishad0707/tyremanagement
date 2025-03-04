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
use App\Http\Controllers\MtyrepositionController;
use App\Http\Controllers\McutlocationController;
use App\Http\Controllers\MacculocationController;
use App\Http\Controllers\MntccutController;
use App\Http\Controllers\MtstypeController;
use App\Http\Controllers\SiteproController;
use App\Http\Controllers\TyreinfoController;
use App\Http\Controllers\TyresiteinfoController;
use App\Http\Controllers\CheckstatusController;
use App\Http\Controllers\TyresizeinfoController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
// Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware(TokenAuth::class);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])
Route::get('get_req', [UserController::class, 'index']);

Route::post('resetPasswordEmail', [UserController::class, 'resetPasswordEmail']);
Route::get('resetPasswordForm', [UserController::class, 'resetPasswordForm']);
Route::post('resetPassword', [UserController::class, 'resetPassword']);

// Route::middleware('auth.token')->group(function () {
Route::middleware([TokenAuth::class])->group(function () {
    Route::get('userlist', [UserController::class, 'userlist']);
    Route::post('adduser', [UserController::class, 'adduser']);
    Route::put('updateuser/{id}', [UserController::class, 'updateuser']);
    Route::delete('deleteuser/{id}', [UserController::class, 'deleteuser']);
    Route::get('getroles', [UserController::class, 'getroles']);
    Route::post('multideleteuser', [UserController::class, 'multideleteuser']);
    Route::get('usersearch', [UserController::class, 'usersearch']);
    // Route::get('GetUserbyRoleId/{role_id}/{parent_id}', [UserController::class, 'GetUserbyRoleId']);
    Route::get('getUserByRoleId', [UserController::class, 'getUserByRoleId']);
    Route::get('getparentroles', [UserController::class, 'getparentroles']);
    Route::resource('permissionmaipping', PermissionmaippingController::class);
    Route::get('getpagename', [PermissionmaippingController::class, 'getpagename']);
    Route::get('getcategory', [PermissionmaippingController::class, 'getcategory']);
    Route::post('checkstatus', [CheckstatusController::class, 'checkstatus']);
    Route::resource('mtyretype', MtyretypeController::class);
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
    Route::get('tyrepositionlist', [MtyrepositionController::class, 'tyrepositionlist']);
    Route::post('inserttyreposition', [MtyrepositionController::class, 'inserttyreposition']);
    Route::put('updatetyreposition/{id}', [MtyrepositionController::class, 'updatetyreposition']);
    Route::delete('deletetyreposition/{id}', [MtyrepositionController::class, 'deletetyreposition']);
    Route::get('cutlocationlist', [McutlocationController::class, 'cutlocationlist']);
    Route::post('insertcutlocation', [McutlocationController::class, 'insertcutlocation']);
    Route::put('updatecutlocation/{id}', [McutlocationController::class, 'updatecutlocation']);
    Route::delete('deletecutlocation/{id}', [McutlocationController::class, 'deletecutlocation']);
    Route::get('accuratelocationlist', [MacculocationController::class, 'accuratelocationlist']);
    Route::post('insertaccuratelocation', [MacculocationController::class, 'insertaccuratelocation']);
    Route::put('updateaccuratelocation/{id}', [MacculocationController::class, 'updateaccuratelocation']);
    Route::delete('deleteaccuratelocation/{id}', [MacculocationController::class, 'deleteaccuratelocation']);
    Route::get('ntccutlist', [MntccutController::class, 'ntccutlist']);
    Route::post('insertntccut', [MntccutController::class, 'insertntccut']);
    Route::put('updatentccut/{id}', [MntccutController::class, 'updatentccut']);
    Route::delete('deletentccut/{id}', [MntccutController::class, 'deletentccut']);
    Route::get('tstypelist', [MtstypeController::class, 'tstypelist']);
    Route::post('inserttstype', [MtstypeController::class, 'inserttstype']);
    Route::put('updatetstype/{id}', [MtstypeController::class, 'updatetstype']);
    Route::delete('deletetstype/{id}', [MtstypeController::class, 'deletetstype']);
    Route::get('siteprojectlist', [SiteproController::class, 'siteprojectlist']);
    Route::post('insertsiteproject', [SiteproController::class, 'insertsiteproject']);
    Route::put('updatesiteproject/{id}', [SiteproController::class, 'updatesiteproject']);
    Route::delete('deletesiteproject/{id}', [SiteproController::class, 'deletesiteproject']);
    Route::get('getsitename', [SiteproController::class, 'getsitename']);
    Route::get('tyreinfolist', [TyreinfoController::class, 'tyreinfolist']);
    Route::post('inserttyreinfo', [TyreinfoController::class, 'inserttyreinfo']);
    Route::put('updatetyreinfo/{id}', [TyreinfoController::class, 'updatetyreinfo']);
    Route::delete('deletetyreinfo/{id}', [TyreinfoController::class, 'deletetyreinfo']);
    Route::get('gettyresize', [TyreinfoController::class, 'gettyresize']);
    Route::get('tyresitelist', [TyresizeinfoController::class, 'tyresitelist']);
    Route::post('inserttyresiteinfo', [TyresizeinfoController::class, 'inserttyresiteinfo']);
    Route::put('updatetyresiteinfo/{id}', [TyresizeinfoController::class, 'updatetyresiteinfo']);
    Route::delete('deletetyresiteinfo/{id}', [TyresizeinfoController::class, 'deletetyresiteinfo']);
    Route::get('getsiteproject', [TyresizeinfoController::class, 'getsiteproject']);
    Route::get('gettruckmodel', [TyresizeinfoController::class, 'gettruckmodel']);
    Route::get('gettyreinfo', [TyresizeinfoController::class, 'gettyreinfo']);
    Route::get('gettyreposition', [TyresizeinfoController::class, 'gettyreposition']);
     Route::get('gettyretypeById', [MtyretypeController::class, 'gettyretypeById']);
    Route::get('tyresitelist', [TyresiteinfoController::class, 'tyresitelist']);
    Route::post('inserttyresiteinfo', [TyresiteinfoController::class, 'inserttyresiteinfo']);
    Route::put('updatetyresiteinfo/{id}', [TyresiteinfoController::class, 'updatetyresiteinfo']);
    Route::delete('deletetyresiteinfo/{id}', [TyresiteinfoController::class, 'deletetyresiteinfo']);
    Route::get('getsiteproject', [TyresiteinfoController::class, 'getsiteproject']);
    Route::get('gettruckmodel', [TyresiteinfoController::class, 'gettruckmodel']);
    Route::get('gettyreinfo', [TyresiteinfoController::class, 'gettyreinfo']);
    Route::get('gettyreposition', [TyresiteinfoController::class, 'gettyreposition']);

    Route::get('gettyretypeByid', [MtypesizeController::class, 'gettyretypeByid']);

});
