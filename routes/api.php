<?php

use Illuminate\Http\Request;

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

Route::post('register', 'API\RegisterController@register');

Route::middleware('auth:api')->group( function () {
    Route::get('blogs', 'API\BlogController@index');
    Route::post('storeBlog', 'API\BlogController@store');
    Route::put('updateBlog/{id}', 'API\BlogController@update');
    Route::get('showBlog/{id}', 'API\BlogController@show');
    Route::delete('deleteBlog/{id}', 'API\BlogController@destroy');
});
