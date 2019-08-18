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
            $table->tinyInteger('is_active');
            $table->string('ws_address',250);
            $table->string('ws_username',250);
            $table->string('ws_password',250);
            $table->bigInteger('ws_update_interval');
            $table->dateTime('ws_update_at')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('m_customer_welcome');
            $table->tinyInteger('w_customer_welcome');
            $table->tinyInteger('m_customer_menu_start');
            $table->tinyInteger('w_customer_menu_start');
            $table->tinyInteger('m_customer_no_charge');
            $table->tinyInteger('w_customer_no_charge');
            $table->tinyInteger('m_customer_inactive');
            $table->tinyInteger('w_customer_inactive');
            $table->tinyInteger('m_demo_welcome');
            $table->tinyInteger('w_demo_welcome');
            $table->tinyInteger('m_demo_menu_start');
            $table->tinyInteger('w_demo_menu_start');
            $table->tinyInteger('m_demo_no_charge');
            $table->tinyInteger('w_demo_no_charge');
            $table->tinyInteger('m_inactive');
            $table->tinyInteger('w_inactive');
            $table->tinyInteger('m_numbers');
            $table->tinyInteger('w_numbers');
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
