<?php

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

// Authentication Routes...
Route::post('login', 'Api\Auth\LoginController@login')->name('api.login');

Route::group(['middleware' => 'auth:api', 'as' => 'api.', 'namespace' => 'Api'], function () {
    /*
     * User Profile Endpoint
     */
    Route::get('user', 'Auth\ProfileController@show')->name('user');

    /*
     * Transctions Endpoints
     */
    Route::apiResource('transactions', 'TransactionsController');

    /*
     * Categories Endpoints
     */
    Route::resource('categories', 'CategoriesController')->names('categories');
});
