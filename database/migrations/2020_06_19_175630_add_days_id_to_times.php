<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDaysIdToTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('times', function (Blueprint $table) {
            // coinsテーブルにdays_idを追加 =======================
            // 
            DB::statement('DELETE FROM times');
            $table->unsignedBigInteger('days_id');
            $table->foreign('days_id')->references('id')->on('days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            // 削除 =======================
            // 
            $table->dropForeign(['days_id']);
            $table->dropColumn('days_id');
        });
    }
}
