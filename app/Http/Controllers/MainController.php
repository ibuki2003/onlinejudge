<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MainController extends Controller{

    public function __construct() {
        if (!config('oj.open_mode'))
            $this->middleware('permission:submit')->only([
                'index',
                'statistics',
            ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('home');
    }
    public function mdeditor(){
        return view('md_editor');
    }
    public function statistics(){
        return view('statistics');
    }
}
