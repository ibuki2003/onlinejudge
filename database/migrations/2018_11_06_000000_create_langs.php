<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('langs')) {
            Schema::create('langs', function (Blueprint $table) {
                $table->string('id',16);
                $table->primary('id');
                $table->text('name');
                $table->string('extension',16);
                $table->text('compile')->nullable(true);
                $table->text('exec');
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
        Schema::dropIfExists('langs');
    }
}
