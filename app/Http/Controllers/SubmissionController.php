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
        if(!config('oj.open_mode')) {
            $this->middleware('auth');
            $this->middleware('permission:admit_users')->only([
                'index',
                'allSubmissionsApi',
            ]);
        }
    }

    public function index(){
        $langs = Lang::all();
        $problems = Problem::all();
        return view('submission/list', ['me' => FALSE, 'langs' => $langs, 'problems' => $problems]);
    }
    public function index_my(){
        $langs = Lang::all();
        $problems = Problem::all();
        return view('submission/list', ['me' => TRUE, 'langs' => $langs, 'problems' => $problems]);
    }


    public function create(int $id = 0){
        $problems = Problem::visibleFilter()->get();
        $langs = Lang::all();

        return view('submission/submit', ['id' => $id, 'problems' => $problems, 'langs' => $langs]);
    }

    public function store(SubmitRequest $request){
        $submission = Submission::create($request->all());

        return redirect()->route('submission', ['id' => $submission->id]);
    }

    public function show($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless($submission->is_visible(),403);
        $source = $submission->get_source();

        return view('submission/submission', ['submission' => $submission]);
    }

    public function allSubmissionsApi(){
        return SubmissionResource::collection(Submission::filterWithRequest()->visibleFilter()->orderBy('id', 'desc')->paginate());
    }
    public function mySubmissionsApi(){
        return SubmissionResource::collection(Submission::ownFilter()->filterWithRequest()->orderBy('id', 'desc')->paginate());
    }

    public function submissionApi($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless($submission->is_visible(),403);

        return new SubmissionResource($submission);
    }

    public function compileResultApi($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless($submission->is_visible(),403);

        if($submission->has_compile_result())
            return $submission->get_compile_result();
        else
            return response(null, 204);
    }

    public function judgeResultApi($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless($submission->is_visible(),403);

        if($submission->has_judge_result())
            return $submission->get_raw_judge_result();
        else
            return response(null, 204);
    }

    public function rejudge($id){
        $submission = Submission::find($id);
        abort_if($submission===NULL,404);
        abort_unless(auth()->user()->has_permission('admit_users'), 403);

        Submission::find($id)->rejudge();
        return response(null, 202);
    }
}
