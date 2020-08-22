<?php

use App\Follows;
use App\Intercoin;
use App\Temp;
use App\User;
use App\Tweet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

Route::middleware('api')->get('/account', function (Request $request) {
    // ログインユーザーのidを取得
    $loginUserId = Auth::user()->id;

    // ログインユーザーのフォロー済みアカウントIDを取得
    $Follows = Follows::select("accountId")->where("userId", "=", $loginUserId)->get();
    
    // フォロー済みがいるかどうか
    if(count($Follows) == 0){
        // 条件なし抽出する
        Log::Debug("条件なし抽出する");
        return Temp::select("id_str", "name", "screen_name", "description", "friends_count", "followers_count", "text")->paginate(50);
    }else{
        Log::Debug("条件あり抽出する");
        // 配列形式にする
        for ($i = 0; $i < count($Follows); $i++) {
            $array[$i] = $Follows[$i]["accountId"];
        }
        // フォローしていないアカウントのみ抽出する
        return Temp::select("id_str", "name", "screen_name", "description", "friends_count", "followers_count", "text")->whereNotIn("id_str", $array)->paginate(50);
    }
});

Route::middleware('api')->get('/trend', function (Request $request) {
    //$keyword = $request->keyword;
    $dt = Carbon::now();
    $dt_new = new Carbon(strval($dt));
    $dt_new->subHour();
    $date = substr($dt_new, 0, 13);
    $date = "2020-08-14 13";
    Log::Debug($date);
    $q_time = $date;

    Log::Debug("api_q:".$q_time);
    // 日付変換処理 日付を返却
    // $q_time = Intercoin::TransformDate($keyword);
    // トレンド用データ集計関数 ここでIntercoinDBが生成される
    $rankingStatus = Intercoin::HourRanking($q_time);
    Log::debug("hour集計完了");

    if ($rankingStatus === "Error") {
        $trends = array("data" => "Error");
        Log::debug($trends);
        return Response::json($trends);
    } else {
        return Intercoin::select("*")->orderBy('tweet', 'desc')->get();
    }
});

Route::middleware('api')->get('/trend/day', function (Request $request) {
    //$keyword = $request->keyword;
    $dt = Carbon::now();
    $dt_new = new Carbon(strval($dt));
    $dt_new->subDay();
    $date = substr($dt_new, 0, 10);
    $date = "2020-08-13";
    Log::Debug($date);
    $q_time = $date;

    Log::Debug("api_q:".$q_time);
    // 日付変換処理 日付を返却
    // $q_time = Intercoin::TransformDate($keyword);
    // トレンド用データ集計関数 ここでIntercoinDBが生成される
    $rankingStatus = Intercoin::DayRanking($q_time);
    Log::debug("day集計完了");

    if ($rankingStatus === "Error") {
        $trends = array("data" => "Error");
        Log::debug($trends);
        return Response::json($trends);
    } else {
        return Intercoin::select("*")->orderBy('tweet', 'desc')->get();
    }
});

Route::middleware('api')->get('/trend/week', function (Request $request) {
    //$keyword = $request->keyword;
    $dt = Carbon::now();
    $dt_new = new Carbon(strval($dt));
    $dt_new->subWeek();
    $date = substr($dt_new, 0, 10);
    //$date = "2020-08-14";
    Log::Debug($date);
    $q_time = $date;

    Log::Debug("api_q:".$q_time);
    // 日付変換処理 日付を返却
    // $q_time = Intercoin::TransformDate($keyword);
    // トレンド用データ集計関数 ここでIntercoinDBが生成される
    $rankingStatus = Intercoin::weekRanking($q_time);
    Log::debug("week集計完了");

    if ($rankingStatus === "Error") {
        $trends = array("data" => "Error");
        Log::debug($trends);
        return Response::json($trends);
    } else {
        return Intercoin::select("*")->orderBy('tweet', 'desc')->get();
    }
});
