<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Temp;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Temp::class, function (Faker $faker) {
    $now = Carbon::now();
    
    return [
        //
        'id_str' => strval($faker->numberBetween($min = 2047483647, $max = 2147483647)),
        'name' => 'Test@仮想通貨ユーザー😳',
        'screen_name' => 'cryptotrend',
        'description' => '仮想通貨好きなテストユーザーです。国内最大級の暗号資産（仮想通貨）のニュースメディア通。「cryptotrend」を運営しています。',
        'friends_count' => strval($faker->numberBetween($min = 1, $max = 9999)),
        'followers_count' => strval($faker->numberBetween($min = 1, $max = 9999)),
        'account_created_at' => $now,
        'following' => strval($faker->boolean),
        'text' => 'LIFULL・Securitize、協業で不動産特定共同事業者向けのSTOスキームの提供開始!楽天ポイントで仮想通貨を交換できちゃいます👏',
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
