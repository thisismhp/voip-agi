<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneNumberTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_number_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name',80);
        });
        DB::unprepared("INSERT INTO `phone_number_types` (`id`, `name`) VALUES
        (1, 'خط ثابت'),
        (2, 'همراه');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_number_types');
    }
}
