<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Submission;
use App\Models\Problem;


class StatisticsController extends Controller
{
    public function problemCreatorApi(){
        // SELECT sender, count(*) as count FROM submissions GROUP BY sender ORDER BY count desc;
        return Problem::select('creator', DB::raw('count(*) as count'))->groupBy('creator')->orderBy('count', 'desc')->get();
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
        return Submission::select('lang', DB::raw('count(*) as count'))->groupBy('lang')->orderBy('count', 'desc')->get();
    }
    public function submissionUserApi(){
        return Submission::select('sender', DB::raw('count(*) as count'))->groupBy('sender')->orderBy('count', 'desc')->get();
    }
}
