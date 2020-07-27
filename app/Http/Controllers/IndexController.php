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
    //public function trendback(){ return view('index.trend_back'); }

    public function trend(Request $request)
    {
        $q = $request->keyword;
        //パラメータがないなら1hourの状態で表示させる
        if (empty($q)) {$q = "1hour";}
        // 日付変換処理
        $q_time = Intercoin::TransformDate($q);
        // トレンド用データ集計関数 ここでIntercoinDBが生成される
        Intercoin::HourRanking($q_time);

        $trends = DB::table('Intercoin')->orderBy('tweet', 'desc')->get();
        return view('index.trend', ['trends' => $trends]);
    }

    public function followsCheckApi(Request $request)
    {
        //autoが返却される
        if($request->user_id){

            $userToken = Auth::user()->twitter_oauth_token;
            $userTokenSecret = Auth::user()->twitter_oauth_token_secret;
            $config = config('twitter');
            $Twitter = new TwitterOAuth($config['api_key'], $config['secret_key'], $userToken, $userTokenSecret);

            // 現在のtempDB(一意のアカウントデータ)のid_strを全て取得
            $tempIdstr = Temp::select('id_str')->orderBy('id_str', 'desc')->get();
            // ログインしているユーザーのアカウントが、取得したid_strをフォローしているかチェックする

            //$loop = count($tempIdstr);
            $loop = 10;

            // 渡ってきた$user_idで中断かどうかを判断する;
            //
            // 処理...
            //
            // そもそもここでなにする？

            for($i = 0; $i < $loop; $i++){

                try {
                    // フォローしているか確認->結果を返す（ボタン表示切り替え用のフラグ取得のため）
                    $twitter_api = $Twitter->get("users/show", 
                            ['user_id' => $tempIdstr[$i]["id_str"] ]);

                    $followRequestSent = $twitter_api->follow_request_sent;
                    $following = $twitter_api->following;
                    Log::Debug($tempIdstr[$i]["id_str"]);

                    $ArrayApiBeforeFollowCheck = array("countNum"=> $i,"following"=> $following, "followRequestSent"=> $followRequestSent);
                    return $ArrayApiBeforeFollowCheck;
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    Log::Debug("== error ==");
                }
                Log::Debug("== 実行されませんよね？？ ==");
            }
        }else{
            Log::debug("auto can not sent Request!");
            return;
        }
    }

    public function follows(Request $request)
    {
        $userid = $request->user_id;
        $userToken = Auth::user()->twitter_oauth_token;
        $userTokenSecret = Auth::user()->twitter_oauth_token_secret;
        $config = config('twitter');

        $Twitter = new TwitterOAuth($config['api_key'], $config['secret_key'], $userToken, $userTokenSecret);
        try {
            // フォロー前に確認
            $twitter_api = $Twitter->get("users/show", 
                    ['user_id' => $userid]);
            $followRequestSent = $twitter_api->follow_request_sent;
            $following = $twitter_api->following;

            if($following){
                //フォローしていた場合
                    Log::Debug($responceCommentFollowIsOk);
                    $responceCommentFollowIsOk = "フォロー済みです";
                    return $responceCommentFollowIsOk;
            }else{
                if($followRequestSent){
                    //リクエストは送っているがフォロー承認されていない場合
                    Log::Debug($responceCommentFollowRequestWaited);
                    $responceCommentFollowRequestWaited = "フォローリクエストの承認まちです";
                    return $responceCommentFollowRequestWaited;
                }else{
                    //リクエストもフォローもしていない場合 -> フォロー処理実行
                    $twitter_api_post = $Twitter->post("friendships/create", ["user_id" => $userid]);
                    $apiFollowed = true;
                    Log::Debug("フォローしました");
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("== error ==");
        }
        $ResponceArrayApiFollowChecked = array("following"=> $following, "followRequestSent"=> $followRequestSent, "apiFollowed"=> $apiFollowed);

        return $ResponceArrayApiFollowChecked;
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
