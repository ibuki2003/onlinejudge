<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
