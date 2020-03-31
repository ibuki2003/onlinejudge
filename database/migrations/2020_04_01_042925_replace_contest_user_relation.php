<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Contest;

class ReplaceContestUserRelation extends Migration
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
                $user_ids = explode(',', $contest->user_ids);
                $contest->users()->attach($user_ids);
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
            $user_ids = $contest->users->pluck('id')->toArray();
            $contest->update(['user_ids' => implode(',', $user_ids)]);
            $contest->users()->detach();
        }
    }
}
