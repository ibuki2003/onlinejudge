<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class MainController extends Controller{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('home');
    }

    public function problemList(){
        $problems = DB::table('problems')->where('open', NULL)->get();
        return view('problems/list', ['problems' => $problems]);
    }

    public function problem($id){
        $problem = DB::table('problems')->where('id', $id)->first();
        abort_if($problem===NULL,404);
        abort_if($problem->open!==NULL && $problem->creator!==auth()->id(),403);

        $content = Storage::disk('data')->get('problems/'.$id.'/main.md');

        return view('problems/problem', ['problem' => $problem, 'id' => $id, 'content' => $content]);
    }

    public function submitForm($id=0){
        $problems = DB::table('problems')->where('open', NULL)->get();
        $langs = DB::table('langs')->get();

        return view('submit', ['id' => $id, 'problems' => $problems, 'langs' => $langs]);
    }

    public function submit(\App\Http\Requests\SubmitRequest $request){
        $problem = $request->input('problem');
        $source = $request->input('source');
        $length = strlen($source);
        $lang = DB::table('langs')->where('id',$request->input('lang'))->first();

        $id=DB::table('submissions')->insertGetId([
            'problem' => $problem,
            'sender' => auth()->id(),
            'size' => $length,
            'lang' => $lang->id,
        ]);

        Storage::disk('data')->makeDirectory('submissions/'.$id);
        Storage::disk('data')->put('submissions/'.$id.'/main.'.$lang->extension, $source);
        DB::table('submissions')->where('id', $id)->update(['status' => 'WJ']);

        return redirect()->route('mySubmissions');

        //return view('submit', ['id' => $id, 'problems' => $problems]);
    }

    public function allSubmissions(){
        $submissions = DB::table('submissions')->get();
        $langs = DB::table('langs')->get()->pluck('name', 'id');
        return view('submissions/list', ['submissions' => $submissions, 'langs' => $langs, 'me' => FALSE]);
    }
    public function mySubmissions(){
        $submissions = DB::table('submissions')->where('sender',auth()->id())->get();
        $langs = DB::table('langs')->get()->pluck('name', 'id');
        return view('submissions/list', ['submissions' => $submissions, 'langs' => $langs, 'me' => TRUE]);
    }

    public function submission($id){
        $submission = DB::table('submissions')->where('id',$id)->first();
        $lang = DB::table('langs')->where('id',$submission->lang)->first();
        $problem = DB::table('problems')->where('id',$submission->problem)->value('title');
        
        $source = Storage::disk('data')->get('submissions/'.$id.'/main.'.$lang->extension);

        $compile_result=NULL;
        if(Storage::disk('data')->exists('submissions/'.$id.'/judge_log.txt')){
            $compile_result=Storage::disk('data')->get('submissions/'.$id.'/judge_log.txt');
        }

        $judge_result=NULL;
        if(Storage::disk('data')->exists('submissions/'.$id.'/judge_log.json')){
            $judge_result=json_decode(Storage::disk('data')->get('submissions/'.$id.'/judge_log.json'));
        }

        return view('submissions/submission', [
            'id' => $id,
            'submission' => $submission,
            'lang' => $lang,
            'problem' => $problem,
            'source' => $source,
            'compile_result' => $compile_result,
            'judge_result' => $judge_result,
        ]);
    }

}
