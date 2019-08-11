<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250);
            $table->char('m_line',50);
            $table->char('w_line',50);
            $table->string('ws_address',250);
            $table->string('ws_username',250);
            $table->string('ws_password',250);
            $table->bigInteger('ws_update_interval');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->longText('f_customer_welcome');
            $table->longText('f_customer_menu_start');
            $table->longText('f_customer_no_charge');
            $table->longText('f_customer_inactive');
            $table->longText('f_demo_welcome');
            $table->longText('f_demo_menu_start');
            $table->longText('f_demo_no_charge');
            $table->longText('f_inactive');
            $table->longText('f_numbers');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
