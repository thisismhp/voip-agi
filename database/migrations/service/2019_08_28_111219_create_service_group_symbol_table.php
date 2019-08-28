<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceGroupSymbolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_group_symbol', function (Blueprint $table) {
            $table->unsignedBigInteger('service_group_id');
            $table->foreign('service_group_id')->references('id')->on('service_groups');
            $table->unsignedBigInteger('symbol_id');
            $table->foreign('symbol_id')->references('id')->on('symbols');
            $table->integer('priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_group_symbol');
    }
}
