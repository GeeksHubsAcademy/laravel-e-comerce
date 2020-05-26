<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('register','UserController@register');
        Route::post('login','UserController@login');

        Route::middleware('auth:api')->group(function(){
            Route::get('logout','UserController@logout');
        });
    });
});

// router.post('/',UserController.register);

// app.use('/users',usersRouter)