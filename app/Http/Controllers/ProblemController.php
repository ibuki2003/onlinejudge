<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Http\Requests\CreateProblemRequest;
use App\Http\Requests\EditProblemRequest;


class ProblemController extends Controller
{
    public function __construct() {
        if (!config('oj.open_mode'))
            $this->middleware('permission:submit')->only([
                'list',
                'problem',
                'editorial',
                'random',
            ]);
    }

    public function list(){
        $problems = Problem::visibleFilter()->sortable()->paginate();
        return view('problems/list', ['problems' => $problems]);
    }

    public function problem($id){
        $problem = Problem::find($id);
        abort_if($problem===NULL,404);
        //abort_if($problem->open!==NULL && $problem->user_id!==auth()->id(),403);
        abort_unless($problem->is_visible(),403);

        return view('problems/problem', ['problem' => $problem]);
    }

    public function zip($id){
        $problem = Problem::find($id);
        abort_if($problem===NULL, 404);
        abort_unless($problem->user_id===auth()->id() || auth()->user()->has_permission('admit_users'),403);
        $dl = $problem->download_zip();
        abort_if($dl === NULL, 404);
        return $dl;
    }

    public function create(){
        return view('problems/create');
    }

    public function store(CreateProblemRequest $request){
        $problem=Problem::create($request->all(),$request->allFiles());
        return redirect()->route('problem', ['id' => $problem->id]);
    }

    public function editorial($id){
        $problem = Problem::find($id);
        abort_if($problem===NULL,404);
        abort_unless($problem->is_visible(),403);
        abort_unless($problem->has_editorial(),404);
        abort_unless($problem->is_editorial_visible(), 403);
        return view('problems/editorial', ['problem' => $problem]);
    }

    public function edit($id){
        $problem = Problem::find($id);
        abort_if($problem===NULL,404);
        abort_unless($problem->user_id === auth()->id(), 403);
        return view('problems/edit', ['problem' => $problem]);
    }
    public function edit_write(EditProblemRequest $request){
        $problem=Problem::find($request->id);
        abort_if($problem===NULL,404);
        abort_unless($problem->user_id === auth()->id(), 403);
        $problem->edit($request->all(),$request->allFiles());
        return redirect()->route('problem', ['id' => $problem->id]);
    }
    public function random(){
        $id=rand(1,Problem::count());
        return redirect()->route('problem', ['id' => $id]);
    }

}
