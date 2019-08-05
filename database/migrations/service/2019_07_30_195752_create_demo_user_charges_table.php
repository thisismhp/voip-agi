<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoUserChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_user_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('demo_users')->onDelete('cascade');
            $table->integer('value');
            $table->unsignedBigInteger('charge_type_id');
            $table->foreign('charge_type_id')->references('id')->on(config('database.connections.manage.database').'.charge_types');
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
        Schema::dropIfExists('demo_user_charges');
    }
}
