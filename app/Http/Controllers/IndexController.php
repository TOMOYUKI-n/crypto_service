<?php

namespace App\Http\Controllers;

use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Account;
use App\Intercoin;
use App\Temp;
use App\Tweet;
use App\User;
use App\Auto;
use App\Follows;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
    public function misslogin()
    {
        return view('misslogin');
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

    /**
     * trend画面遷移時、最初に実行される
     */
    public function trend(Request $request) 
    {
        $q = $request->keyword;

        //パラメータがないなら1hourの状態で表示させる
        if (empty($q)) {$q = "1hour";}
        // 日付変換処理
        $q_time = Intercoin::TransformDate($q);
        // トレンド用データ集計関数 ここでIntercoinDBが生成される
        $hourRankingStatus = Intercoin::HourRanking($q_time);
        
        if($hourRankingStatus == "Error"){
            $trends = "Error";
            Log::Debug($hourRankingStatus);
            Log::debug($trends);

            // return $trends;
            return view('index.trend', ['trends' => $trends]);
        }else{
            $trends = DB::table('Intercoin')->orderBy('tweet', 'desc')->get();
            Log::Debug($hourRankingStatus);
            Log::debug($trends);
            return view('index.trend', ['trends' => $trends]);
        }
    }

    /**
     * フォローをしているかチェックする関数
     */
    public function followCheck(Request $request)
    {
        $userid = $request->user_id;
        $userToken = Auth::user()->twitter_oauth_token;
        $userTokenSecret = Auth::user()->twitter_oauth_token_secret;
        $config = config('twitter');
        $Twitter = new TwitterOAuth($config['api_key'], $config['secret_key'], $userToken, $userTokenSecret);
        Log::debug("followCheck 実行");

        try {
            $twitter_api = $Twitter->get("friendships/lookup",['user_id' => $userid]);
            $apiRes = $twitter_api[0]->connections;
            // $followRequestSent = $twitter_api->follow_request_sent;
            // $following = $twitter_api->following;
            //$res = array( "following" => $following, "followRequestSent" => $followRequestSent);
            $res = array( "apiRes" => $apiRes);
            Log::debug("followCheck 完了");
            return $res;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("== error ==");
        }
    }

    /**
     * フォローをする処理
     */
    public function follows(Request $request)
    {
        $userId = $request->user_id;
        $loginId = Auth::user()->id;
        $userToken = Auth::user()->twitter_oauth_token;
        $userTokenSecret = Auth::user()->twitter_oauth_token_secret;
        $config = config('twitter');
        $Twitter = new TwitterOAuth($config['api_key'], $config['secret_key'], $userToken, $userTokenSecret);

        try {
            Log::debug("IndexController follows 実行");
            $twitter_api_post = $Twitter->post("friendships/create", ["user_id" => $userId]);
            $following = $twitter_api_post->following;
            
            // フォローを保存
            Follows::GeneratefollowsList($userId,$loginId);
            //$res = array("following" => "following");
            $res = array("following" => $following);
            Log::debug("IndexController follows 実行完了　フォローしました");
            return $res;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("IndexController follows 失敗しました");
        }
    }

    // /**
    //  * 自動フォローを実行しているユーザーの状態を保存 flg=1
    //  */
    // public function autoFlgSaved($loginId)
    // {
    //     try{
    //         $auto = new Auto;
    //         $auto["userId"] = $loginId;
    //         $auto["autoFlg"] = "1";
    //         $auto->save();
    //     }catch(\Exception $e){
    //         Log::error($e->getMessage());
    //         Log::Debug("error: Autoのsaveに失敗しました");
    //     }
    // }

    // /**
    //  * 自動フォローを実行しているユーザーの状態を保存 flg=0へ
    //  */
    // public function autoFlgDelete($loginId){
    //     try{
    //         $auto = new Auto;
    //         $auto["userId"] = $loginId;
    //         $auto["autoFlg"] = "0";
    //         $auto->save();
    //         return;
    //     }catch(\Exception $e){
    //         Log::error($e->getMessage());
    //         Log::Debug("error: Autoのsaveに失敗しました");
    //     }
    // }
    
    /**
     * 自動フォローをする処理
     * バッチ実行リストに登録する処理
     */
    public function autoFollows(Request $request)
    {
        $loginId = $request->loginId;
        // responce定義
        $autoResOk = array("status" => 200);
        $autoResErr = array("status" => "Error");
        
        // ログインユーザーがautoフラグ1であるか確認する
        $loginUser = Auto::select("autoFlg")->where("userId", "=", $loginId)->first();
        var_dump($loginUser);

        Log::debug("== 処理実行 ==");
        if($loginUser == null){ $loginUser["autoFlg"] = 0; };
        Log::debug($loginUser);

        if($loginUser["autoFlg"] == 0){
            // 0なら1に変更し,実行対象にする
            try {
                Auto::autoFlgSaved($loginId);
                return $autoResOk;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                Log::Debug("== error ==");
                return $autoResErr;
            }
        }else{
            // 1なら0に変更し,実行対象外にする
            try {
                Auto::autoFlgDelete($loginId);
                return $autoResOk;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                Log::Debug("== error ==");
                return $autoResErr;
            }
        }
    }

    public function news()
    {
        //遷移するごとに関数実行
        $news = User::get_news("仮想通貨", 100);
        //json オブジェクトの形式でviewへ渡す
        $newsline = json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        //ユーザ情報を取得
        $user = Auth::user();
        //ビュー表示
        return view('index.news', ['user' => $user, 'newsline' => $newsline]);
    }
}
