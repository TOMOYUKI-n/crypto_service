<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hours', function (Blueprint $table) {
            // hoursテーブルの作成 ========================
            // 一時間分のtweet数を格納
            $table->bigIncrements('id');
            $table->unsignedBigInteger('times_id');
            $table->foreign('times_id')->references('id')->on('times')->onDelete('cascade');
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
        Schema::dropIfExists('hours');
    }
}
