<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name',100);
            $table->char('nation_code',20);
            $table->char('birth_date',20)->nullable();
            $table->unsignedBigInteger('province_id');
            $table->foreign('province_id')->references('id')->on(config('database.connections.manage.database').'.provinces');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on(config('database.connections.manage.database').'.cities');
            $table->char('address',255)->nullable();
            $table->char('phone_number',20)->nullable();
            $table->integer('time_charge')->nullable();
            $table->dateTime('date_charge')->nullable();
            $table->tinyInteger('end_charge_checked')->default(1);
            $table->longText('end_charge_comment')->nullable();
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('customers');
    }
}
