<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveContestProblemsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn('problem_ids');
        });
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn('problem_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->string('problem_ids', 255)->default("");
            $table->string('problem_points', 255)->default("");
        });
    }
}
