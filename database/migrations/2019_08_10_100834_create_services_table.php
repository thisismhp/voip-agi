<?php

use App\Service;
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
            foreach (Service::$FILES as $FILE){
                $table->tinyInteger("m_$FILE")->nullable();
                $table->tinyInteger("w_$FILE")->nullable();
            }
            $table->tinyInteger('m_numbers')->nullable();
            $table->tinyInteger('w_numbers')->nullable();
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
