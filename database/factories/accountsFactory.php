<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Account;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    $now = Carbon::now();

    return [
        //
        'id_str' => strval($faker->numberBetween($min = 2047483647, $max = 2147483647)),
        'name' => $faker->name,
        'screen_name' => $faker->name,
        'description' => $faker->realText($maxNbChars = 140,$indexSize = 2),
        'friends_count' => strval($faker->numberBetween($min = 1, $max = 99999)),
        'followers_count' => strval($faker->numberBetween($min = 1, $max = 99999)),
        'account_created_at' => $now,
        'following' => strval($faker->boolean),
        'text' => $faker->realText($maxNbChars = 140,$indexSize = 2),
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
