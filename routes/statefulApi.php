<?php

use Illuminate\Http\Request;

Route::get('/submissions', 'SubmissionController@allSubmissionsApi')->middleware('permission:admit_users');
Route::get('/submissions/me', 'SubmissionController@mySubmissionsApi')->middleware('web');

Route::prefix('statistics')->group(function () {
    Route::get('problem_creator', 'StatisticsController@problemCreatorApi');
    Route::get('problem_difficulty', 'StatisticsController@problemDifficultyApi');

    Route::get('submission_status', 'StatisticsController@submissionStatusApi');
    Route::get('submission_lang', 'StatisticsController@submissionLangApi');
    Route::get('submission_user', 'StatisticsController@submissionUserApi');
});
