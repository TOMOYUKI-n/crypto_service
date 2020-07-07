<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minutes', function (Blueprint $table) {
            // minutesテーブルの再作成 ========================
            $table->bigIncrements('id');
            $table->datetime('datetime');
            $table->string('BTC')->nullable(true);
            $table->string('ETH')->nullable(true);
            $table->string('ETC')->nullable(true);
            $table->string('LSK')->nullable(true);
            $table->string('FCT')->nullable(true);
            $table->string('XRP')->nullable(true);
            $table->string('XEM')->nullable(true);
            $table->string('LTC')->nullable(true);
            $table->string('BCH')->nullable(true);
            $table->string('MONA')->nullable(true);
            $table->string('XLM')->nullable(true);
            $table->string('QTUM')->nullable(true);
            $table->string('DASH')->nullable(true);
            $table->string('ZEC')->nullable(true);
            $table->string('XMR')->nullable(true);
            $table->string('REP')->nullable(true);
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
        Schema::dropIfExists('minutes');
    }
}
