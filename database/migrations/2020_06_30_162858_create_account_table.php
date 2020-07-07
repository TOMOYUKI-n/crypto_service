<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            //account情報を保存する
            $table->bigIncrements('id');
            $table->string('id_str');
            $table->text('name')->nullable(true);
            $table->string('screen_name')->nullable(true);
            $table->text('description')->nullable(true);
            $table->string('friends_count')->nullable(true);
            $table->string('followers_count')->nullable(true);
            $table->datetime('account_created_at')->nullable(true);
            $table->string('following')->nullable(true);
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
        Schema::dropIfExists('account');
    }
}
