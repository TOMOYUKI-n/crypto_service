<?php

use App\Follows;
use App\Temp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => 'api'], function () {
//     Route::post('/account', 'IndexController@follows');
// });

// 元のapi / account
// Route::middleware('api')->get('/account', function (Request $request) {
//     return Temp::orderBy('id_str', 'desc')->paginate(50);
// });

Route::middleware('api')->get('/account', function (Request $request) {
    //Log::Debug($request);
    $loginUserId = Auth::user()->id;

    $Follows = Follows::select("accountId")->where("userId", "=", $loginUserId)->get();
    // 配列形式にする
    for ($i = 0; $i < count($Follows); $i++) {
        $array[$i] = $Follows[$i]["accountId"];
    }
    // フォローしていないアカウントのみ抽出する
    return Temp::select("id_str", "name", "screen_name", "description", "friends_count", "followers_count", "text")->whereNotIn("id_str", $array)->paginate(50);
    //return Temp::orderBy('id_str', 'desc')->paginate(50);
});
