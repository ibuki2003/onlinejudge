<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;


class AdminController extends Controller{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('permission:admit_users');
    }

    public function manage_users(){
        return view('administration/manage_users',['users'=>User::all()]);
    }

    public function manage_users_apply(Request $request){
        $data = $request->input('users_data');
        if(!is_array($data))return;
        foreach ($data as $id => $value) {
            $user = User::find($id);
            if(isset($value['permission']))
                $user->permission = $value['permission'];
            $user->save();
        }
    }

}
