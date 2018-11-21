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

Route::get('mdeditor', 'MainController@mdeditor')->name('md_editor');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'MainController@index')->name('top');
    Route::get('problems', 'ProblemController@list')->name('problems');
    Route::get('problems/{id}', 'ProblemController@problem')->where('id', '\d+')->name('problem');
    Route::get('problems/{id}/editorial', 'ProblemController@editorial')->where('id', '\d+')->name('problem_editorial');
    Route::get('problems/{id}/edit', 'ProblemController@edit')->where('id', '\d+')->name('problem_edit');
    Route::post('problems/{id}/edit', 'ProblemController@edit_write')->where('id', '\d+');

    Route::get('submissions/me', 'SubmissionController@index_my')->name('submissions_me');
    Route::get('submissions/{id}', 'SubmissionController@show')->where('id', '\d+')->name('submission');
    Route::post('submissions/{id}/rejudge', 'SubmissionController@rejudge')->where('id', '\d+')->name('submission_rejudge');

    Route::group(['middleware' => ['permission:submit']], function () {
        Route::get('submit/{id?}', 'SubmissionController@create')->where('id', '\d+')->name('submit');
        Route::post('submit', 'SubmissionController@store');
    });

    Route::group(['middleware' => ['permission:create_problem']], function () {
        Route::get('problems/new', 'ProblemController@create')->name('create_problem');
        Route::post('problems/new', 'ProblemController@store');
    });
    Route::group(['middleware' => ['permission:create_contest']], function () {});
    Route::group(['middleware' => ['permission:admit_users']], function () {
        Route::get('submissions', 'SubmissionController@index')->name('submissions');
    });


    
});
