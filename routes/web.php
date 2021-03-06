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

Route::get('/', 'MainController@index')->name('top');
Route::get('problems', 'ProblemController@list')->name('problems');
Route::get('problems/random', 'ProblemController@random')->name('random_problem');
Route::get('problems/{id}', 'ProblemController@problem')->where('id', '\d+')->name('problem');
Route::get('problems/{id}/editorial', 'ProblemController@editorial')->where('id', '\d+')->name('problem_editorial');

Route::get('statistics', 'MainController@statistics')->name('statistics');

Route::get('submissions/{id}', 'SubmissionController@show')->where('id', '\d+')->name('submission');

Route::get('contests', 'ContestController@list')->name('contests');
Route::get('contests/{id}', 'ContestController@show')->where('id', '\d+')->name('contest');

Route::get('submissions', 'SubmissionController@index')->name('submissions');


Route::group(['middleware' => ['auth']], function () {
    Route::get ('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('change_password');
    Route::post('change_password', 'Auth\ChangePasswordController@changePassword');

    Route::get('problems/{id}/edit', 'ProblemController@edit')->where('id', '\d+')->name('problem_edit');
    Route::post('problems/{id}/edit', 'ProblemController@edit_write')->where('id', '\d+');
    Route::get('problems/{id}/zip', 'ProblemController@zip')->where('id', '\d+')->name('problem_zip');

    Route::get('submissions/me', 'SubmissionController@index_my')->name('submissions_me');
    Route::post('submissions/{id}/rejudge', 'SubmissionController@rejudge')->where('id', '\d+')->name('submission_rejudge');

    Route::group(['middleware' => ['permission:submit']], function () {
        Route::get('submit/{id?}', 'SubmissionController@create')->where('id', '\d+')->name('submit');
        Route::post('submit', 'SubmissionController@store');

        Route::post('contests/{id}/participate', 'ContestController@participate')->where('id', '\d+')->name('contest_participate');
        Route::post('contests/{id}/cancel_participate', 'ContestController@cancel_participate')->where('id', '\d+')->name('contest_cancel_participate');
        Route::get('contests/{id}/edit', 'ContestController@edit')->where('id', '\d+')->name('contest_edit');
        Route::post('contests/{id}/edit', 'ContestController@edit_write')->where('id', '\d+');
    });

    Route::group(['middleware' => ['permission:create_problem']], function () {
        Route::get('problems/new', 'ProblemController@create')->name('create_problem');
        Route::post('problems/new', 'ProblemController@store');
    });
    Route::group(['middleware' => ['permission:create_contest']], function () {
        Route::get('contests/new', 'ContestController@create')->name('create_contest');
        Route::post('contests/new', 'ContestController@store');
    });

    Route::get('admin/manage_users', 'AdminController@manage_users')->name('manage_users');
    Route::post('admin/manage_users', 'AdminController@manage_users_apply');
});
