<?php

use Illuminate\Database\Seeder;

class TempTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Temp::class, 969)->create();
    }
}
