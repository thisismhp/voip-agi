<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCharge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_users', function (Blueprint $table) {
            $table->integer('time_charge')->nullable()->after('phone_number');
            $table->dateTime('date_charge')->nullable()->after('time_charge');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('time_charge')->nullable()->after('phone_number');
            $table->dateTime('date_charge')->nullable()->after('time_charge');
            $table->tinyInteger('end_charge_checked')->default(1)->after('date_charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demo_users', function (Blueprint $table) {
            $table->dropColumn('time_charge');
            $table->dropColumn('date_charge');
            $table->dropColumn('end_charge_checked');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('time_charge');
            $table->dropColumn('date_charge');
        });
    }
}
