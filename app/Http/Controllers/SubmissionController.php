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
        return view('submission/list', ['submissions' => $submissions, 'me' => FALSE]);
    }
    public function index_my(){
        $submissions = Submission::ownFilter()->get();
        return view('submission/list', ['submissions' => $submissions, 'me' => TRUE]);
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
        abort_unless($submission->is_visible(),403);
        $source = $submission->get_source();

        return view('submission/submission', ['submission' => $submission]);
    }

    public function allSubmissionsApi(){
        return SubmissionResource::collection(Submission::orderBy('id', 'desc')->paginate());
    }
    public function mySubmissionsApi(){
        return SubmissionResource::collection(Submission::ownFilter()->orderBy('id', 'desc')->paginate());
    }

    public function rejudge($id){
        $submission = Submission::find($id);
        $problem = Problem::find($submission->problem);
        abort_if($submission===NULL,404);
        abort_unless($submission->sender === auth()->id() || $problem->creator == auth()->id(), 403);
        
        Submission::where('id',$id)->update(['status'=>'WR']);
        return redirect()->route('submission', ['id'=>$id]);
    }
}
