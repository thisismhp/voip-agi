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
            $table->char('queue_id',50);
            $table->tinyInteger('customer_is_free')->default(0);
            $table->tinyInteger('demo_is_free')->default(0);
            $table->integer('demo_first_date_charge')->default(0);
            $table->integer('demo_first_time_charge')->default(0);
            $table->unsignedBigInteger('demo_use_charge_type_id');
            $table->foreign('demo_use_charge_type_id')->references('id')->on('charge_types');
            $table->string('ws_address',250);
            $table->string('ws_username',250);
            $table->string('ws_password',250);
            $table->bigInteger('ws_update_interval');
            $table->dateTime('ws_update_at')->nullable();
            $table->string('sms_address',250)->nullable();
            $table->string('sms_username',250)->nullable();
            $table->string('sms_password',250)->nullable();
            $table->string('sms_number',250)->nullable();
            $table->longText('sms_text')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('menu_opr_key')->nullable()->default(null);
            $table->integer('no_charge_opr_key')->nullable()->default(null);
            $table->integer('no_charge_sms_key')->nullable()->default(null);
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
