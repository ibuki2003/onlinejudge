<?php

use Illuminate\Http\Request;

Route::get('/submissions', 'SubmissionController@allSubmissionsApi')->middleware('permission:admit_users');
Route::get('/submissions/me', 'SubmissionController@mySubmissionsApi')->middleware('web');
Route::get('/submissions/{id}', 'SubmissionController@submissionApi')->where('id', '\d+')->middleware('web');
Route::get('/submissions/{id}/compile_result', 'SubmissionController@compileResultApi')->where('id', '\d+')->middleware('web');
Route::get('/submissions/{id}/judge_result', 'SubmissionController@judgeResultApi')->where('id', '\d+')->middleware('web');

Route::get('/contests/standings/{id}', 'ContestController@standingsApi')->where('id', '\d+')->middleware('web');

Route::get('aggregate', 'StatisticsController@aggregateApi');
