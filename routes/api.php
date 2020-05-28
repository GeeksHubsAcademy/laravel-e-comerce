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
    Route::group([
        'prefix'=>'categories',
        'middleware'=>'auth:api'
    ],function(){
        Route::post('','CategoryController@insert');
        Route::put('{id}','CategoryController@update');
    });
    Route::group([
        'prefix'=>'products',
        'middleware'=>'auth:api'
    ],function(){
        Route::get('','ProductController@getAll');
        Route::get('restore/{id}','ProductController@restore');
        Route::post('','ProductController@insert');
        Route::post('/image/{id}','ProductController@uploadImage');
        Route::put('{id}','ProductController@update');
        Route::delete('{id}','ProductController@delete');
    });
});
// Route::resource('payments', 'PaymentController');