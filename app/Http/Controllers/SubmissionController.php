<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\SubmitRequest;
use App\Models\Submission;

use App\Models\Problem;
use App\Http\Resources\SubmissionResource;
use App\Models\Lang;

class SubmissionController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $submissions = Submission::all();
        $langs = Lang::all();
$problems = Problem::all();
        return view('submission/list', ['submissions' => $submissions, 'me' => FALSE, 'langs' => $langs, 'problems' => $problems]);
    }
    public function index_my(){
        $submissions = Submission::ownFilter()->get();
        $langs = Lang::all();
$problems = Problem::all();
        return view('submission/list', ['submissions' => $submissions, 'me' => TRUE, 'langs' => $langs, 'problems' => $problems]);
    }


    public function create(int $id = 0){
        $problems = Problem::visibleFilter()->get();
        $langs = Lang::all();

        return view('submission/submit', ['id' => $id, 'problems' => $problems, 'langs' => $langs]);
    }

    public function store(SubmitRequest $request){
        Submission::create($request->all());

        return redirect()->route('submissions_me');
    }

    public function show($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless($submission->is_visible(),403);
        $source = $submission->get_source();

        return view('submission/submission', ['submission' => $submission]);
    }

    public function allSubmissionsApi(){
        return SubmissionResource::collection(Submission::filterWithRequest()->orderBy('id', 'desc')->paginate());
    }
    public function mySubmissionsApi(){
        return SubmissionResource::collection(Submission::ownFilter()->filterWithRequest()->orderBy('id', 'desc')->paginate());
    }
    
    public function rejudge($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless(auth()->user()->has_permission('admit_users'), 403);
        
        Submission::find($id)->rejudge();
        return redirect()->route('submission', ['id'=>$id]);
    }
}
