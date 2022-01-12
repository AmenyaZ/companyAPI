<?php


use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
// use App\Http\Controllers\API\ControllerExample;
use App\Http\Controllers\API\OrganizationController;


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


Route::post('/register', [AuthController::class, 'register']) 
            ->middleware('restrictothers');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);

    //        ->middleware('auth:api');

Route::group(['middleware' => 'auth:api'], function() {

    Route::apiResource('/organization', OrganizationController::class);
    
    Route::apiResource('/role', ControllerExample::class);

    // add a new user with writer scope
    Route::post('users/writer', [ControllerExample::class, 'createWriter']);

    // add a new user with subscriber scope
    Route::post('users/subscriber', [ControllerExample::class, 'createSubscriber']);
    // delete a user
    Route::delete('users/{id}', [ControllerExample::class, 'deleteUser']);
});
















/*Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});
*/
    // list all roles
//Route::get('roles', [ControllerExample::class, 'role']);
    // get a role
//Route::get('roles/{id}', [ControllerExample::class, 'singleRole']);
    // add a new role
//Route::post('roles', [ControllerExample::class, 'createRole']);
    // updating a role
//Route::put('roles/{id}', [ControllerExample::class, 'updateRole']);
    // delete a role
//Route::delete('roles/{id}', [ControllerExample::class, 'deleteRole']);
