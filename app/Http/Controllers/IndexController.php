<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Tweet;
use App\User;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;
use App\Account;
use App\Intercoin;

class IndexController extends Controller
{
    //
    public function test()
    {
        return view('test');
    }

    public function top()
    {
        return view('top');
    }
    public function term()
    {
        return view('term');
    }
    public function policy()
    {
        return view('policy');
    }
    //public function trendback(){ return view('index.trend_back'); }

    public function trend(Request $request)
    {   
        header("Access-Control-Allow-Origin: *");  //CORS
        header("Access-Control-Allow-Headers: Origin, X-Requested-With");

        $q = $request->keyword;
        //パラメータがないなら1hourの状態で表示させる
        if (empty($q)){ $q = "1hour"; }
        // 日付変換処理
        $q_time = Intercoin::TransformDate($q);
        // トレンド用データ集計関数 ここでIntercoinDBが生成される
        Intercoin::HourRanking($q_time);

        $trends = DB::table('Intercoin')->orderBy('tweet', 'desc')->get();
        return view('index.trend', ['trends' => $trends]);
    }


    public function account()
    {
        //このままではページ更新ごとにuser/searchが実行されてしまうので変更する
        $userinfo = Account::GetUsersInfo();
        return view('index.account', ['userinfo' => $userinfo]);
    }


    public function news()
    {   
        //遷移するごとに関数実行
        $news = User::get_news("仮想通貨",100);
        //json オブジェクトの形式でviewへ渡す
        $newsline = json_encode($news, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        //ユーザ情報を取得
        $user = Auth::user();
        //ビュー表示
        return view('index.news',['user' => $user, 'newsline' => $newsline]);
    }
}
