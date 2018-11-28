<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Change Controller
    |--------------------------------------------------------------------------
    */
    public function showChangePasswordForm(){
        return view('auth.changepassword');
    }

    public function changePassword(ChangePasswordRequest $request){
        $messages = new MessageBag;
        if (!(Hash::check($request->get('old_password'), auth()->user()->password))) {
            $messages->add('old_password',__('auth.failed'));
            return redirect()->back()->withErrors($messages);
        }
        if($request->get('old_password')===$request->get('new_password')){
            $messages->add('new_password',__('auth.change_same'));
            return redirect()->back()->withErrors($messages);
        }
        //Change Password
        $user = auth()->user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        return redirect()->back()->with('success',__('auth.password_changed'));
    }

}
