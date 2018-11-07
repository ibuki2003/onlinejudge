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


Route::get ('signin', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('signin', 'Auth\LoginController@login');
Route::post('signout', 'Auth\LoginController@logout')->name('logout');

Route::get ('signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('signup', 'Auth\RegisterController@register');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'MainController@index')->name('top');
    Route::get('problems', 'MainController@problemList')->name('problems');
    Route::get('problems/{id}', 'MainController@problem')->where('id', '\d+')->name('problem');

    Route::get('submit/{id?}', 'MainController@submitForm')->where('id', '\d+')->name('submit');
    Route::post('submit', 'MainController@submit');

    Route::get('submissions', 'MainController@allSubmissions')->name('submissions');
    Route::get('submissions/me', 'MainController@mySubmissions')->name('submissions_me');
    Route::get('submissions/{id}', 'MainController@submission')->where('id', '\d+')->name('submission');
});
