<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome')->name('welcome');

Auth::routes();

// Change Password Routes
Route::get('change-password', 'Auth\ChangePasswordController@show')->name('password.change');
Route::patch('change-password', 'Auth\ChangePasswordController@update')->name('password.change');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', 'TransactionsController@index')->name('home');

    /**
     * Transactions Routes
     */
    Route::resource('transactions', 'TransactionsController');
});
