<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Time;
use Faker\Generator as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

$factory->define(App\Hour::class, function (Faker $faker) {
    $timesId  = Time::pluck('id')->all();
    $now = Carbon::now();

    return [
        //
        'times_id' => $faker->randomElement($timesId),
        'BTC'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'ETH'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'ETC'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'LSK'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'FCT'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'XRP'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'XEM'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'LTC'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'BCH'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'MONA' => strval($faker->numberBetween($min = 1, $max = 990)),
        'XLM'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'QTUM' => strval($faker->numberBetween($min = 1, $max = 990)),
        'DASH' => strval($faker->numberBetween($min = 1, $max = 990)),
        'ZEC'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'XMR'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'REP'  => strval($faker->numberBetween($min = 1, $max = 990)),
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
