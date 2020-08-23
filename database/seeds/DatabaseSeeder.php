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
                // UserTableSeeder::class
                DaysTableSeeder::class,
                TimesTableSeeder::class,
                CoinTableSeeder::class,
                HoursTableSeeder::class,
                WeeksTableSeeder::class,
                AccountTableSeeder::class,
                TempTableSeeder::class,
                
            ]
        );
    }
}
