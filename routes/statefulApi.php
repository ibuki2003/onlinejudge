<?php

use Illuminate\Http\Request;

Route::get('/submissions', 'SubmissionController@allSubmissionsApi')->middleware('permission:8');
Route::get('/submissions/me', 'SubmissionController@mySubmissionsApi')->middleware('web');
