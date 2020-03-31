<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Contest;

class ReplaceContestProblemRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Contest::all() as $contest) {
            if ($contest->user_ids !== '') {
                $problem_ids = explode(',', $contest->problem_ids);
                $problem_points = explode(',', $contest->problem_points);
                foreach ($problem_ids as $idx => $problem_id) {
                    $contest->problems()->attach(
                        $problem_id,
                        [
                            'idx' => $idx,
                            'point' => $problem_points[$idx]
                        ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (Contest::all() as $contest) {
            $problems = $contest->problems;

            $problem_ids = $contest->problems->pluck('id')->toArray();
            $problem_points = $contest->problems->pluck('pivot.point')->toArray();
            $contest->update([
                'problem_ids' => implode(',', $problem_ids),
                'problem_points' => implode(',', $problem_points)
            ]);
            $contest->problems()->detach();
        }
    }
}
