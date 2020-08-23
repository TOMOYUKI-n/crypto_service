<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                DaysTableSeeder::class,
                TimesTableSeeder::class,
                HoursTableSeeder::class,
                WeeksTableSeeder::class,
                AccountTableSeeder::class,
                TempTableSeeder::class,
                CoinTableSeeder::class,
                UserTableSeeder::class
            ]
        );
    }
}
