<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\CreateContestRequest;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\User;
use Kyslik\ColumnSortable\Sortable;

class ContestController extends Controller
{
    use Sortable;
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list() {
        $contests = Contest::sortable()->paginate();
        return view('contests/list', ['contests' => $contests]);
    }
    
    public function create(){
        $problems = Problem::all();
        return view('contests/create', ['problems' => $problems]);
    }

    public function store(CreateContestRequest $request){
        $contest=Contest::create($request->all());
        // return nothing because this function should be called via ajax and doing redirection here does not do anything
    }
    
    public function edit($id){
        $contest = Contest::find($id);
        abort_if($contest===NULL,404);
        abort_if(auth()->id() != $contest->creator, 403);
        $problems = Problem::all();
        return view('contests/edit', ['problems' => $problems, 'contest' => $contest]);
    }
    public function edit_write(CreateContestRequest $request, $id){
        $contest = Contest::find($id);
        abort_if($contest === NULL, 404);
        abort_if(auth()->id() != $contest->creator, 403);
        $contest->edit($request->all());
    }

    public function show($id){
        $contest = Contest::find($id);
        abort_if($contest===NULL,404);
        $problem_ids = explode(",", $contest->problem_ids);
        foreach ($problem_ids as &$problem_id) {
            $problem_id = (int) $problem_id;
        }
        unset($problem_id);
        $problems = Problem::whereIn('id', $problem_ids)->sortable()->paginate();

        return view('contests/contest', ['contest' => $contest, 'problems' => $problems]);
    }
    public function participate($id) {
        $contest = Contest::find($id);
        abort_if($contest===NULL,404);
        abort_if(!$contest->can_participate(), 403); // already pariticipated
        $contest->participate();
        return redirect()->route('contest', ['id'=>$id]);
    }
    public function cancel_participate($id) {
        $contest = Contest::find($id);
        abort_if($contest===NULL,404);
        abort_if(!$contest->can_cancel_participate(), 403); // not pariticipated
        $contest->cancel_participate();
        return redirect()->route('contest', ['id'=>$id]);
    }
    public function formatTime($timestamp) {
        if ($timestamp == -1) return "--:--";
        else if ($timestamp >= 60 * 60) return sprintf("%2d:%02d:%02d", $timestamp / (60 * 60), $timestamp / 60 % 60, $timestamp % 60);
        else return sprintf("%02d:%02d", $timestamp / 60, $timestamp % 60);
    }
    public function standingsApi($id) {
        $contest = Contest::find($id);
        abort_if($contest===NULL,404);
        $problem_ids = [];
        if ($contest->problem_ids !== "") $problem_ids = explode(",", $contest->problem_ids);
        foreach ($problem_ids as &$problem_id) {
            $problem_id = (int) $problem_id;
        }
        unset($problem_id);
        $user_ids = [];
        if ($contest->user_ids !== "") $user_ids = explode(",", $contest->user_ids);
        $submissions = Submission::whereBetween('time', [$contest->start_time, $contest->end_time])
                                 ->whereIn('user_id', $user_ids)
                                 ->whereIn('problem_id', $problem_ids)->get();
        
        // info per user per problem
        $scores = [];
        $penalties = [];
        $accept_time = [];
        $submission_num = []; // just for penalty calculation
        $judging = [];
        $has_ac = [];
        // init
        foreach ($user_ids as $user_id) {
            foreach ($problem_ids as $problem_id) {
                $scores[$user_id][$problem_id] = 0;
                $penalties[$user_id][$problem_id] = -1;
                $accept_time[$user_id][$problem_id] = -1;
                $submission_num[$user_id][$problem_id] = 0;
                $has_ac[$user_id][$problem_id] = false;
                $judging[$user_id][$problem_id] = false;
            }
        }
        // process submissions
        foreach ($submissions as $submission) {
            if ($submission->point > 0) {
                if ($scores[$submission->user_id][$submission->problem_id] < $submission->point) {
                    $scores[$submission->user_id][$submission->problem_id] = $submission->point;
                    if ($submission_num[$submission->user_id][$submission->problem_id] > 0)
                        $penalties[$submission->user_id][$submission->problem_id] = $submission_num[$submission->user_id][$submission->problem_id];
                    else if ($submission_num[$submission->user_id][$submission->problem_id] == 0)
                        $penalties[$submission->user_id][$submission->problem_id] = -1; // cancel CE penalty
                    $accept_time[$submission->user_id][$submission->problem_id] = strtotime($submission->time) - strtotime($contest->start_time);
                    $has_ac[$submission->user_id][$submission->problem_id] = true;
                }
                $submission_num[$submission->user_id][$submission->problem_id]++;
            } else if ($submission->status === 'CE') {
                if ($penalties[$submission->user_id][$submission->problem_id] == -1)
                    $penalties[$submission->user_id][$submission->problem_id] = 0; // (0)
            } else if (in_array($submission->status, ['SB', 'WJ', 'WR'])) {
                $judging[$submission->user_id][$submission->problem_id] = true;
            } else { // WA, TLE, RE etc...
                $submission_num[$submission->user_id][$submission->problem_id]++;
            }
        }
        // penalty without AC
        foreach ($user_ids as $user_id) {
            foreach ($problem_ids as $problem_id) {
                if (!$has_ac[$user_id][$problem_id] && $submission_num[$user_id][$problem_id] > 0)
                    $penalties[$user_id][$problem_id] = $submission_num[$user_id][$problem_id];
            }
        }
        $data = [];
        // store problems data
        $data['problems'] = [];
        $problem_points = explode(',', $contest->problem_points);
        foreach ($problem_ids as $index => $problem_id) {
            $problem = Problem::find($problem_id)->toArray();
            $problem['point'] = $problem_points[$index];
            $data['problems'][] = $problem;
        }
        // apply point_alloted
        foreach ($user_ids as $user_id) {
            foreach ($problem_ids as $index => $problem_id) {
                $scores[$user_id][$problem_id] *= $problem_points[$index];
                $scores[$user_id][$problem_id] /= 100;
            }
        }
        // calculate score/penalty/time for users
        $data['users'] = [];
        foreach ($user_ids as $user_id) {
            $cur_user_data = [];
            $cur_user_data['id'] = $user_id;
            $cur_user_data['data'] = [];
            
            $cur_user_data['score_sum'] = 0;
            $cur_user_data['penalty_sum'] = -1;
            $cur_user_data['time_all'] = -1;
            foreach ($problem_ids as $problem_id) {
                $cur_data = [
                    'score' => $scores[$user_id][$problem_id],
                    'penalty' => $penalties[$user_id][$problem_id],
                    'time' => $this->formatTime($accept_time[$user_id][$problem_id]),
                    'judging' => $judging[$user_id][$problem_id]
                ];
                $cur_user_data['data'][] = $cur_data;
                $cur_user_data['score_sum'] += $scores[$user_id][$problem_id];
                if ($penalties[$user_id][$problem_id] >= 0 && $has_ac[$user_id][$problem_id]) {
                    if ($cur_user_data['penalty_sum'] == -1) $cur_user_data['penalty_sum'] = 0; 
                    $cur_user_data['penalty_sum'] += $penalties[$user_id][$problem_id];
                }
                if ($cur_user_data['time_all'] < $accept_time[$user_id][$problem_id])
                    $cur_user_data['time_all'] = $accept_time[$user_id][$problem_id];
            }
            if ($cur_user_data['penalty_sum'] > 0) $cur_user_data['time_all'] += $cur_user_data['penalty_sum'] * $contest->penalty * 60;
            $data['users'][] = $cur_user_data;
        }
        // sort users by rank
        usort($data['users'], function(&$a, &$b) {
            if ($a['score_sum'] != $b['score_sum']) return $b['score_sum'] - $a['score_sum'];
            if ($a['score_sum'] == 0) return $b['penalty_sum'] - $a['penalty_sum'];
            return $a['time_all'] - $b['time_all']; // shorter time is better
        });
        // calculate rank
        foreach ($data['users'] as $index => &$cur_data) {
            if (!$index || $data['users'][$last_rank]['score_sum'] != $cur_data['score_sum'] ||
                           $data['users'][$last_rank]['time_all'] != $cur_data['time_all']) $last_rank = $index;
            $cur_data['rank'] = $last_rank + 1;
        }
        unset($cur_data);
        
        // convert timestamp to time string
        foreach ($data['users'] as &$cur_data) {
            $cur_data['time_all'] = $this->formatTime($cur_data['time_all']);
        }
        unset($cur_data);
        
        /*
            return value format
            {
                problems : [], // problems
                users : array<{
                    id : , // user id
                    rank : , // rank(1-indexed of course)
                    score_sum : , // sum of scores of the all problems
                    penalty_sum : , // sum of penalties of the all problems
                    time_all : , // time of last point update
                    data : array<{
                        score, // scores for each problems
                        penalty, // penalties for each problems, -1 if no submission yet
                        time, // last new acception time
                        judging, // whether waiting for judge
                    }>,
                }>,
            }
        */
        return $data;
    }
}
