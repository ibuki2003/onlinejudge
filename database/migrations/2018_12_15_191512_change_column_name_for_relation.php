<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnNameForRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->renameColumn('creator', 'user_id');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->renameColumn('sender', 'user_id');
        });
        
        Schema::table('submissions', function (Blueprint $table) {
            $table->renameColumn('lang', 'lang_id');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->renameColumn('problem', 'problem_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->renameColumn('user_id', 'creator');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'sender');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->renameColumn('lang_id', 'lang');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->renameColumn('problem_id', 'problem');
        });
    }
}
