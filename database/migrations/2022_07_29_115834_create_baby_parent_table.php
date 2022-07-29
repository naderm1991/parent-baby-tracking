<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabyParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_user', function (Blueprint $table) {
            $table->integer('baby_id');
            $table->integer('user_id');
            $table->primary(['baby_id', 'user_id']);
            $table->index('baby_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baby_user', function (Blueprint $table) {
            //
        });
    }
}
