<?php

use Illuminate\Http\Request;

Route::get('/submissions', 'SubmissionController@allSubmissionsApi')->middleware('permission:admit_users');
Route::get('/submissions/me', 'SubmissionController@mySubmissionsApi')->middleware('web');

Route::get('aggregate', 'StatisticsController@aggregateApi');
