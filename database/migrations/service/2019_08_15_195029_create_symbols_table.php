<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSymbolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbols', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('symbolId')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->longText('m_file')->nullable();
            $table->longText('w_file')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on(config('database.connections.manage.database').'.units');
            $table->text('fName')->nullable();
            $table->text('eName')->nullable();
            $table->text('buyPriceFormatted')->nullable();
            $table->text('sellPriceFormatted')->nullable();
            $table->text('transPriceFormatted')->nullable();
            $table->text('buysellDiff')->nullable();
            $table->text('vol')->nullable();
            $table->text('volTick')->nullable();
            $table->text('direction')->nullable();
            $table->text('transType')->nullable();
            $table->text('dsabt')->nullable();
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
        Schema::dropIfExists('symbols');
    }
}
