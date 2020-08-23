<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テスト実行時から1週間分の日付
        for($i = 7; $i > -1; $i--){
            
            $now = Carbon::now();
            $now->subDays($i);

            DB::table('days')->insert([
                [
                    'date' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);
        }

    }
}
