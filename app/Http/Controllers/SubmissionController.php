<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\SubmitRequest;
use App\Models\Submission;

use App\Models\Problem;
use App\Models\Lang;

class SubmissionController extends Controller
{
    public function submitForm(int $id = 0){
        $problems = Problem::visibleFilter()->get();
        $langs = Lang::all();

        return view('submit', ['id' => $id, 'problems' => $problems, 'langs' => $langs]);
    }

    public function submit(SubmitRequest $request){
        Submission::submit($request->all());
        
        return redirect()->route('submissions_me');
    }

    public function allSubmissions(){
        $submissions = Submission::all();
        $langs = Lang::get_map();
        return view('submissions/list', ['submissions' => $submissions, 'langs' => $langs, 'me' => FALSE]);
    }
    public function mySubmissions(){
        $submissions = Submission::ownFilter()->get();
        $langs = Lang::get_map();
        return view('submissions/list', ['submissions' => $submissions, 'langs' => $langs, 'me' => TRUE]);
    }

    public function submission($id){
        $submission = Submission::find($id);
        abort_unless($submission->is_visible(),403);
        $source = $submission->get_source();

        return view('submissions/submission', ['submission' => $submission]);
    }

    
}
