<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Auto;
use App\Follows;
use App\Intercoin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function trends(Request $request)
    {
        $q = $request->keyword;

        //パラメータがないなら1hourの状態で表示させる
        if (empty($q)) {$q = "1hour";}
        // 日付変換処理
        $q_time = Intercoin::TransformDate($q);
        // トレンド用データ集計関数 ここでIntercoinDBが生成される
        $hourRankingStatus = Intercoin::HourRanking($q_time);

        if ($hourRankingStatus == "Error") {
            $trends = array("data" => "Error");
            Log::Debug($hourRankingStatus);
            Log::debug($trends);

            return $trends;
        } else {
            $trends = DB::table('Intercoin')->orderBy('tweet', 'desc')->get();
            Log::Debug($hourRankingStatus);
            Log::debug($trends);
            return $trends;
        }
    }

    /**
     * フォローをしているかチェックする関数
     */
    public function followCheck(Request $request)
    {
        // ユーザートークン設定
        $userid = $request->user_id;
        $userToken = Auth::user()->twitter_oauth_token;
        $userTokenSecret = Auth::user()->twitter_oauth_token_secret;
        $config = config('twitter');
        $Twitter = new TwitterOAuth($config['api_key'], $config['secret_key'], $userToken, $userTokenSecret);
        Log::debug("followCheck 実行");

        // フォロー処理を制限数超えていないかチェック
        $friendshipsCreateParam = "/friendships/lookup";
        $params = array("resources" => "friendships");
        $apiUrl = $Twitter->get("application/rate_limit_status", $params);
        $friendships = $apiUrl->resources->friendships;
        $counter = $friendships->$friendshipsCreateParam->remaining;
        Log::Debug($counter);

        if ($counter == 0) {
            $res = array("apiRes" => 1);
            Log::debug("残り回数が0です.処理を終了します");
            return $res;
        } else {
            try {
                $twitter_api = $Twitter->get("friendships/lookup", ['user_id' => $userid]);
                $apiRes = $twitter_api[0]->connections;
                $res = array("apiRes" => $apiRes);
                Log::debug("followCheck 完了");
                return $res;

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                Log::Debug("== error ==");
            }
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
            Follows::GeneratefollowsList($userId, $loginId);
            //$res = array("following" => "following");
            $res = array("following" => $following);
            Log::debug("IndexController follows 実行完了　フォローしました");
            return $res;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("IndexController follows 失敗しました");
        }
    }

    /**
     * フォローしているデータを配列として取得する
     */
    public function getAuthFollowData()
    {
        try {
            $loginUser = Auth::user()->id;
            $followingData = Follows::select("accountId")->where("userId", "=", $loginUser)->get();
            Log::Debug($followingData);
            return $followingData;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("error: フォローしているデータを配列として取得するのに失敗しました");
        }
    }

    /**
     * 自動フォローをする処理
     * バッチ実行リストに登録する処理
     */
    public function autoFollows(Request $request)
    {
        $loginId = $request->loginId;
        // responce定義
        $autoResOk = array("status" => 200);
        $autoResErr = array("status" => 400);

        // ログインユーザーがautoフラグ1であるか確認する
        $loginUser = Auto::select("autoFlg")->where("userId", "=", $loginId)->first();
        // レコードなしの場合は新規登録なので1をセットする
        Log::debug("== 処理実行 ==");

        // 新規登録なら0　後に１で登録させる
        if ($loginUser == null) {
            $loginUser["autoFlg"] = 0;
        };

        try {
            if ($loginUser["autoFlg"] == 0) {
                // 0なら1に変更し,実行対象にする
                Auto::autoFlgSavedOne($loginId);
            } else {
                // 1なら0に変更し,実行対象外にする
                Auto::autoFlgSavedZero($loginId);
            }
            Log::Debug("autoFollows で実行リストへの登録が完了しました");
            return $autoResOk;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("autoFollows でバッチ対象者の保存に失敗しました");
            return $autoResErr;
        }

    }

    public function getUsers()
    {
        $users = Auth::user();
        Log::Debug($users);
        return $users;
    }

    public function news()
    {
        //遷移するごとに関数実行
        $news = User::get_news("仮想通貨", 100);
        //json オブジェクトの形式でviewへ渡す
        $newsline = json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        //ユーザ情報を取得
        // $user = Auth::user();
        //ビュー表示
        return view('index.news', ['newsline' => $newsline]);
    }
}
