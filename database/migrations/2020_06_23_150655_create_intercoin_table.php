<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntercoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Intercoin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('get_dates')->nullable(true);
            $table->string('coin_name')->nullable(true);
            $table->integer('tweet')->nullable(true);
            $table->string('high')->nullable(true);
            $table->string('low')->nullable(true);
            $table->integer('days_id')->nullable(false);
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
        Schema::dropIfExists('Intercoin');
    }
}
