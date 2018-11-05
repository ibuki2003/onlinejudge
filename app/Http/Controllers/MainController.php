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
}
