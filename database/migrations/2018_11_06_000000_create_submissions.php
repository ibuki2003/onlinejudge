<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('submissions')) {
            Schema::create('submissions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('problem');
                $table->string('sender',16);
                $table->string('status')->default('SB');
                $table->integer('point')->default(0);
                $table->string('lang');
                $table->integer('size')->default(0);
                $table->timestamp('time')->useCurrent();
            });
        }else{
            Schema::table('submissions', function (Blueprint $table) {
                $table->string('status')->default('SB')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
