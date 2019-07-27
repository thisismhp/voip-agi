<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoUserTimeChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_user_time_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demo_user_id');
            $table->foreign('demo_user_id')->references('id')->on('demo_users')->onDelete('cascade');
            $table->integer('value');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_user_time_charges');
    }
}
