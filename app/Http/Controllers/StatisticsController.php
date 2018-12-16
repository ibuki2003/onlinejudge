<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Submission;
use App\Models\Problem;


class StatisticsController extends Controller
{
    public function problemCreatorApi(){
        // SELECT user_id, count(*) as count FROM submissions GROUP BY user_id ORDER BY count desc;
        return Problem::select('user_id', DB::raw('count(*) as count'))->groupBy('user_id')->orderBy('count', 'desc')->get();
    }
    public function problemDifficultyApi(){
        $list=Problem::select('difficulty', DB::raw('count(*) as count'))->groupBy('difficulty')->pluck('count', 'difficulty');
        $res=[];
        for($i = 1;$i<=config('oj.difficulty_max');$i++){
            if($list->has($i))
                $res[]=(int)$list[$i];
            else
                $res[]=0;
        }
        return $res;
    }
    public function submissionStatusApi(){
        return Submission::select('status', DB::raw('count(*) as count'))->groupBy('status')->orderBy('count', 'desc')->get();
    }
    public function submissionLangApi(){
        return Submission::select('lang_id', DB::raw('count(*) as count'))->groupBy('lang_id')->orderBy('count', 'desc')->get();
    }
    public function submissionUserApi(){
        return Submission::select('user_id', DB::raw('count(*) as count'))->groupBy('user_id')->orderBy('count', 'desc')->get();
    }
}
