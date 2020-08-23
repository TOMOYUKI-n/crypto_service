<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //twitterユーザーは作成しない。
        factory(App\User::class, 3)->create();
    }
}
